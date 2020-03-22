<?php
/**
 * User: mlawson
 * Date: 11/12/18
 * Time: 12:30 PM
 */

namespace NeubusSrm\Services;


use Auth;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Carbon\Carbon;
use Crypt;
use Gate;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;
use NeubusSrm\Events\NeulogActionEvent;
use NeubusSrm\Events\UserWasUpdated;
use NeubusSrm\Http\Resources\UserCollection;
use NeubusSrm\Jobs\SendTempPasswordEmail;
use NeubusSrm\Lib\Exceptions\NeuEntityNotFoundException;
use NeubusSrm\Lib\Exceptions\NeuSrmException;
use NeubusSrm\Lib\Exceptions\NeuUserInvalidCredentialsException;
use NeubusSrm\Models\Auth\User;
use NeubusSrm\Repositories\UserRepository;
use NeubusSrm\Repositories\VerificationTokenRepository;
use OTPHP\TOTP;
use ParagonIE\ConstantTime\Base32;
use ParagonIE\ConstantTime\Base64;

/**
 * Class UserService
 * @package NeubusSrm\Services
 */
class UserService extends NeuSrmService
{

    /**
     * @var string
     */
    const RANDOM_STR = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    /**
     * @var integer
     */
    const RANDOM_LENGTH = 62;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var VerificationTokenRepository
     */
    private $verificationRepo;

    /**
     * @var Writer
     */
    private $qrCodeWriter;

    /**
     * UserService constructor.
     * @param UserRepository $userRepository
     * @param VerificationTokenRepository $verificationTokenRepository
     */
    public function __construct(UserRepository $userRepository, VerificationTokenRepository $verificationTokenRepository) {
        $this->userRepository = $userRepository;
        $this->verificationRepo = $verificationTokenRepository;

        // to avoid calling "new" in a method, we'll put these in the constructor so we can resolve with the container
        $qrCodeRenderer = new ImageRenderer(
            new RendererStyle(250), new ImagickImageBackEnd()
        );
        $this->qrCodeWriter = new Writer($qrCodeRenderer);
    }

    /**
     * @param string $email
     * @param string $password
     * @param bool $willUseMfa
     * @return string
     * @throws NeuSrmException
     * @throws NeuUserInvalidCredentialsException
     * @throws \Throwable
     */
    public function loginUser(string $email, string $password, bool $willUseMfa = false) : string {
        $userValid = false;
        $userEmail = $this->userRepository->getUserByEmailLower($email)->email;
        if ($willUseMfa) {
            // just check, don't start a session yet
            $userValid = Auth::validate(['email' => $userEmail, 'password' => $password]);
        } else {
            // NSN-866 if the user doesn't have mfa, they can stay logged in...forever...
            // however that will be controlled via middleware
            $userValid = Auth::attempt(['email' => $userEmail, 'password' => $password], true);

            event(new NeulogActionEvent('Login', $this->userRepository->getLoggableParams(Auth::id())));
        }
        if ($userValid) {
            // generate login token
            try {

                return $this->addVerificationToken($userEmail);
            } catch (NeuSrmException $exception) {
                throw $exception;
            }
        } else {
            throw new NeuUserInvalidCredentialsException('Invalid Login');
        }
    }

    /**
     * @throws NeuSrmException
     */
    public function logoutUser() : void {
        if (Auth::check()) {
            // and we're going to do this again if someone logs out to round out the process
            // so we basically want to say: if you are logging in, you will never see the verify mfa page
            // if you have mfa and you need it, you will see the mfa entry page anyway, thus verifying it
            // anyway.
            $this->setVerifyMfa(Auth::id(), false);
            event(new NeulogActionEvent('Logout', $this->userRepository->getLoggableParams(\Auth::id())));
        }
        Auth::logout();
    }

    /**
     * @param string $email
     * @param string $token
     * @return bool
     * @throws \Throwable
     */
    public function verifyUser(string $email, string $token) : bool {
        $user = Auth::user() ?? $this->userRepository->getUserByEmailLower($email);
        if ($this->verifyVerificationToken($user->email, $token)) {
            // handles a case where a user logs in on another browser and we end up
            // in an infinite loop of verifications because of session weirdness, see NSN-1225
            $this->setVerifyMfa($user->id, false);
            $this->resetVerification($user->verification_token_id, $user);

            return true;
        }
        $this->logoutUser();
        return false;
    }

    /**
     * @param string $email
     * @return bool
     * @throws NeuSrmException
     * @throws \Throwable
     */
    public function userHasMfa(string $email) : bool {
        try {
            return $this->userRepository->getUserByEmailLower($email)->has_mfa;
        } catch (NeuSrmException | \Throwable $exception) {
            throw $exception;
        }
    }

    /**
     * @param string $email
     * @return array
     * @throws NeuSrmException
     * @throws \Throwable
     */
    public function userMfaNeedsSetup(string $email) : array {
        try {
            $user = $this->userRepository->getUserByEmailLower($email);
            $needsSetup = ($user->otp_secret === null && $user->has_mfa)
                || ($user->has_mfa && $user->otp_verified === false);
            if ($needsSetup) {
                return [
                    'needs_setup' => true,
                    'mfa_setup_uri' => route('login.mfa.setup'),
                    'mfa_verify_uri' => route('login.mfa.verify')
                ];
            }
        } catch (NeuSrmException | \Throwable $exception) {
            throw $exception;
        }
        return [
            'needs_setup' => false
        ];
    }

    /**
     * @param string $email The provisioning url for the qr code
     * @return array
     * @throws NeuEntityNotFoundException
     * @throws \Throwable
     */
    public function setupUserMfa(string $email, bool $alt): array {
        try {
            $userEmail = $this->userRepository->getUserByEmailLower($email)->email;
            $secret = '';
            if (!$alt) {
                $qrUri = $this->getQrCodeUri($userEmail);
            }
            else{
                $qrArr = $this->getQrCodeUriAlt($userEmail);
                $qrUri = $qrArr['url'];
                $secret = $qrArr['secret'];
            }
            $encodedQr = Base64::encode($this->qrCodeWriter->writeString($qrUri));
            return [
                'encoded_qr' => $encodedQr,
                'verification_token' => $this->addVerificationToken($userEmail),
                'secret' => $secret
            ];
        } catch (NeuEntityNotFoundException | \Throwable $exception) {
            throw $exception;
        }
    }

    /**
     * @param string $email
     * @param string $verificationToken
     * @return string
     * @throws NeuUserInvalidCredentialsException
     * @throws \NeubusSrm\Lib\Exceptions\UserNotFoundException
     * @throws \Throwable
     */
    public function resetUserMfaSetup(string $email, string $verificationToken) : string {
        // just let the exception bubble up instead of rethrowing
        $userInstance = $this->userRepository->getUserByEmailLower($email);
        $userEmail = $userInstance->email;
        if ($this->verifyVerificationToken($userEmail, $verificationToken)) {
            $this->resetVerification($userInstance->verification_token_id, $userInstance);
            $this->userRepository->setMfaSecret($userEmail, null);
            $this->userRepository->updateUser($userInstance->id, ['otp_verified' => false, 'company_id' => $userInstance->company_id]);
        }
        else {
            throw new NeuUserInvalidCredentialsException('Verification token check failed');
        }
        return route('logout');
    }
	/**
	 * @param string $email
	 * @param bool $setEnable
	 * @throws NeuSrmException
	 * @throws \Throwable
	 */
	public function setMfa(string $email, bool $setEnable = true) : void {
		try {
			if ($setEnable) {
				$this->userRepository->enableMfa($email);
			}
			else {
				$this->userRepository->disableMfa($email);
			}
		}
		catch (NeuSrmException | \Throwable $exception) {
			throw $exception;
		}
	}


    /**
     * TODO: offload this to some kind of helper function
     * @param string $email
     * @return string
     * @throws \NeubusSrm\Lib\Exceptions\UserNotFoundException
     * @throws \Throwable
     */
    private function getQrCodeUri(string $email) : string {
        $totpInstance = TOTP::create();
        $this->userRepository->setMfaSecret($email, $totpInstance->getSecret());
        $totpInstance->setLabel($email);
        $totpInstance->setIssuer('Neubus SRM');
        return $totpInstance->getProvisioningUri();
    }

    /**
     * @param string $email
     * @return array
     * @throws \NeubusSrm\Lib\Exceptions\UserNotFoundException
     * @throws \Throwable
     */
    private function getQrCodeUriAlt(string $email) : array {
        $secret = Base32::encode($this->randSecret());
        $totpInstance = TOTP::create($secret);
        $this->userRepository->setMfaSecret($email, $totpInstance->getSecret());
        $totpInstance->setLabel($email);
        $totpInstance->setIssuer('Neubus SRM');
        $secret = $totpInstance->getSecret();
        $url = $totpInstance->getProvisioningUri();
        return ['url' => $url, 'secret' => $secret];
    }

    /**
     * @return string
     * @throws \Exception
     */
    private function randSecret() : string {
        $str = '';
        $max = mb_strlen(self::RANDOM_STR, '8bit') - 1;
        for ($i = 0; $i < 16; $i++) {
            $str .= self::RANDOM_STR[random_int(0, $max)];
        }
        return $str;
    }

	/**
	 * Generate a token that can be used to verify a request that should be protected
	 * @param string $email
	 * @return string
	 * @throws \NeubusSrm\Lib\Exceptions\UserNotFoundException
	 * @throws \Throwable
	 */
	public function addVerificationToken(string $email) : string {
		$publicToken = Crypt::encryptString($email.Auth::getSession()->getId());
		$privateToken = Crypt::encryptString($email.Auth::getSession()->getId());
		$verifyToken = $this->verificationRepo->saveNewToken($privateToken);
		$this->userRepository->addVerificationTokenForUser($email, $verifyToken);
		return $publicToken;
	}


    /**
     * @param string $userId
     * @param bool $verify
     * @throws NeuSrmException
     */
    public function setVerifyMfa(string $userId, bool $verify = true) : void {
        $this->userRepository->updateUser($userId, ['verify_mfa' => $verify], false);
    }

    /**
     * @param string $email
     * @param string $currentToken
     * @return bool
     * @throws \Throwable
     */
    private function verifyVerificationToken(string $email, string $currentToken) : bool {
        $srmUser = $this->userRepository->getUserByEmailLower($email);
        $storedToken = $srmUser->token->token;
        $clearStoredToken = Crypt::decryptString($storedToken);
        $clearCurrToken = Crypt::decryptString($currentToken);
        return $clearCurrToken === $clearStoredToken;
    }

    /**
     * @param int $tokenId
     * @param User $user
     * @throws \Throwable
     */
    private function resetVerification(int $tokenId, User $user) : void {
        $srmUser = $user;
        $srmUser->verification_token_id = null;
        $srmUser->save();
        $this->verificationRepo->getTokenById($tokenId)->delete();
    }

    /**
     * @return LengthAwarePaginator
     */
    public function index() : LengthAwarePaginator {
        return $this->userRepository->getUsers(25, true);
    }

    /**
     * @param array $request
     * @return array
     * @throws \NeubusSrm\Lib\Exceptions\NeuEntityExistsException
     */
    public function createUser(array $request) : array {
        $this->userRepository->checkExistingEmail($request['email'], true);
        $password = $this->createTempPassword();
        if (array_key_exists('password', $request) && $request['password'] != null && $request['password'] != '') {
            $password = $request['password'];
        }
        $params = [
            'name' => $request['name'],
            'email' => $request['email'],
            'company_id' => $request['company_id'],
            'password' => bcrypt($password),
            'role' => $request['role'],
            'is_temp' => true,
        ];
        $userArr = $this->userRepository->createUser($params, $password, $request['email'])->toArray();
        return $userArr;
    }

    /**
     * @return string
     */
    public function createTempPassword() : string {
        $str = '';
        for ($i = 0; $i < 7; $i++) {
            $str .= UserService::RANDOM_STR[rand(0, UserService::RANDOM_LENGTH - 1)];
        }
        return $str;
    }


    public function read($id) {
        return $this->userRepository->findUser($id);
    }

    public function updateUser(array $request, $id) : void {
        $initialUser = $this->userRepository->getUserById($id);
        if ($initialUser->email !== $request['email']) {
            $this->userRepository->checkExistingEmail($request['email'], false);
        }
        $params = [
            'name' => $request['name'],
            'email' => $request['email'],
            'company_id' => $request['company_id'],
            'role' => $request['role']
        ];
        if (isset($request['password'])) {
            $params['password'] = bcrypt($request['password']);
            if ($params['password'] != ''){
                $params['is_temp'] = true;
            }
        }


        $this->userRepository->updateUser($id, $params);
        $updatedUser = $this->userRepository->getUserById($id);

        $initialUserHasMfa = config("srm.requires_mfa.{$initialUser->role}");
        $updatedUserHasMfa = config("srm.requires_mfa.{$updatedUser->role}");

        if (!$initialUserHasMfa && $updatedUserHasMfa) {
            event(new UserWasUpdated($updatedUser));
        }
    }

    /**
     * @param string $password
     */
    public function updateUserPassword(string $password) : void {
        $this->userRepository->updateUserPassword($password);
    }

    /**
     * @param string $email
     * @throws \Throwable
     */
    public function resetUserPassword(string $email) : void {
        $password = $this->createTempPassword();
        $this->userRepository->resetUserPasswordByEmail($email, $password);
        $message = view('emails.resetpassword', ['password' => $password])->render();
        $subject = 'NeuDocs SRM: Account password has been changed';
        SendTempPasswordEmail::dispatch($password, $email, $message, $subject);
    }

    /**
     * @param string $email
     * @param bool $isOtpVerified
     * @throws \Throwable|\NeubusSrm\Lib\Exceptions\UserNotFoundException
     */
    public function setUserOtpIsVerfied(string $email, bool $isOtpVerified) : void {
        $user = $this->userRepository->getUserByEmailLower($email);
        $this->userRepository->updateUser($user->id, ['otp_verified' => $isOtpVerified], false);
    }

    /**
     * @param $id
     * @return bool
     * @throws NeuSrmException
     */
    public function delete($id) : bool {

        return $this->userRepository->deleteUser($id);
    }

    /**
     * @param bool $byCompany
     * @param bool $canUseAnyUser
     * @return array
     */
    public function getAllUsers(bool $byCompany = true, bool $canUseAnyUser = false) : array {
        $user = Auth::getUser();
        if ($byCompany)
            $users = $this->userRepository->getUsersByCompanyId($user->company_id);
        else
            $users =  $this->userRepository->getUsers();

        return $users->toArray(request());
    }

    /**
     * @param string $keyword
     * @return UserCollection
     */
    public function searchUser(string $keyword) : UserCollection {
        return $this->userRepository->searchUser($keyword);
    }

    /**
     * @param string $companyId
     * @return array
     */
    public function getUsersByCompanyId(string $companyId) {
        $users = $this->userRepository->getUsersByCompanyId($companyId);
        return $users->toArray(request());
    }

    /**
     * @param string $userId
     * @param string $projectId
     * @throws \NeubusSrm\Lib\Exceptions\UserNotFoundException
     */
    public function updateDefaultProject(string $userId, string $projectId) : void {
        $this->userRepository->updateDefaultProjectForUser($userId, $projectId);
    }

    /**
     * Keep this general, don't bind it to mfa. It's purpose is
     * to create a custom timeout catch, instead of having a session timeout on its own
     * Basically, if this method fires, it's time to hit the verification page instead
     * of logout and dead session. Leave sessions on never expire for now.
     * @return bool
     * @throws \NeubusSrm\Lib\Exceptions\NeuSrmException
     */
    public function authedUserIsTimedOut() : bool {
        $lastAccessed = $this->userRepository->getUserLastAccessTime(Auth::id());
        $currentTimeoutPeriod = (int)config('srm.neu_timeout_period');
        $currentDiff = Carbon::now()->diffInSeconds($lastAccessed);
        // due to accessor override, last_verified should be an int now

        // left operand determines whether or not we should check in the first place
        // second determines if we really do need to check based on the last verified time
        // if there is too much space both, we absolutely cannot let the user view anything
        // but the verification page
        // for example, if the current diff and current timeout are mismatch, that could
        // just mean the user hasn't actually done anything yet
        // however, each time the verification page is loaded, the session will be updated
        // this prevents that situation. you could call it the exit condition
        return $currentDiff >= $currentTimeoutPeriod;
    }

    /**
     * @return string
     */
    public function getDefaultRoute() {
        $routeToUse = ''; // TODO: temp
        \Log::info(Gate::allows('it.menu'));
        \Log::info(route('it.home'));
        if (Gate::allows('it.menu')) {
            $routeToUse = route('it.home');
        } else {
            if (Gate::allows('neubus.menu')) {
                $routeToUse = route('todo.home');
            } elseif (Gate::allows('client.menu')) {
                $routeToUse = route('client.home');
            } else {
                if (Gate::allows('admin.menu')) {
                    $routeToUse = route('admin.home');
                }
            }
        }
        return $routeToUse;
    }


    /**
     * @param string $email
     * @return User
     * @throws \NeubusSrm\Lib\Exceptions\UserNotFoundException
     * @throws \Throwable
     */
    public function getUserByEmail(string $email) : User {
        return $this->userRepository->getUserByEmail($email);
    }

    /**
     * @param array $userData
     * @param string $userId
     * @throws NeuSrmException
     * @throws \NeubusSrm\Lib\Exceptions\NeuEntityExistsException
     * @throws \NeubusSrm\Lib\Exceptions\UserNotFoundException
     */
    public function updateUserProfile(array $userData, string $userId) : void {
        $initialUser = $this->userRepository->getUserById($userId);
        if ($initialUser->email !== $userData['email']) {
            $this->userRepository->checkExistingEmail($userData['email'], false);
        }
        $params = [
            'name' => $userData['name'],
            'email' => $userData['email'],
        ];
        if (isset($userData['password'])) {
            $params['password'] = bcrypt($userData['password']);
        }

        $this->userRepository->updateUserProfile($userId, $params);
    }

    /**
     * @param User $initialUser
     * @param array $updatedUser
     * @return bool
     */
    public function userNeedsLogout(User $initialUser, array $updatedUser) : bool {
        if ($initialUser->email !== $updatedUser['email']) {
            return true;
        }

        if (isset($updatedUser['password']) && !(Hash::check($updatedUser['password'], $initialUser->password))) {
            return true;
        }

        return false;
    }

    /**
     * @param string $sortBy
     * @param string $order
     * @param string $keyword
     * @return array
     * @throws \Throwable
     */
    public function userSearch(string $sortBy, string $order, string $keyword) : array {
        $query = $this->userRepository->searchQuery($keyword);
        $query = $this->userRepository->orderQuery($query, $sortBy, $order);
        $users = $this->userRepository->userSearch($query);
        return $users;
    }
}
