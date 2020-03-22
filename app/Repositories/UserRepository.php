<?php
/**
 * User: mlawson
 * Date: 11/12/18
 * Time: 12:31 PM
 */

namespace NeubusSrm\Repositories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use NeubusSrm\Events\NeulogModelEvent;
use NeubusSrm\Http\Resources\UsersCollection;
use NeubusSrm\Jobs\SendTempPasswordEmail;
use NeubusSrm\Lib\Exceptions\NeuEntityExistsException;
use NeubusSrm\Lib\Exceptions\UserNotFoundException;
use NeubusSrm\Lib\Wrappers\Collections\NeuTypedCollection;
use NeubusSrm\Models\Auth\User;
use NeubusSrm\Models\Auth\VerificationToken;

use NeubusSrm\Http\Resources\UserCollection;
/**
 * Class UserRepository
 * @package NeubusSrm\Repositories
 */
class UserRepository implements NeuSrmRepository
{
    /**
     * @var array
     */
    const SEARCH_ARR = ['name' => ['type' => 'nojoin', 'col' => 'name'],
        'email' => ['type' => 'nojoin', 'col' => 'email'],
        'role' => ['type' => 'nojoin', 'col' => 'role'],
        'company_name' => ['type' => 'join', 'table' => 'companies', 'col' => 'company_name', 'relation' => 'company',
            'foreignKey' => 'companies.id', 'localKey' => 'users.company_id']];


    /**
	 * @return string
	 */
	public function getModelClass(): string {
		return User::class;
	}

    /**
     * @param string $userId
     * @return User
     * @throws \NeubusSrm\Lib\Exceptions\NeuSrmException
     */
	public function getUserById(string $userId) : User {
	    $user = User::whereId($userId)->first();
	    neu_throw_if($user === null, UserNotFoundException::class, 'No user exists with that id');

	    return $user;
    }

	/**
	 * @param string $email
	 * @return User
	 * @throws \Throwable|UserNotFoundException
	 */
	public function getUserByEmail(string $email) : User {
		$srmUser = User::whereEmail($email)->first();
		throw_if($srmUser == null,
			UserNotFoundException::class, 'No user found for this email');
		return $srmUser;
	}

    /**
     * @param string $email
     * @return User
     * @throws \Throwable
     */
	public function getUserByEmailLower(string $email) : User {
        $srmUser = User::whereRaw('lower(email) = ?', [strtolower($email)])->first();
        neu_throw_if($srmUser === null,
            UserNotFoundException::class, 'No user found for this email');
        return $srmUser;
    }

	/**
	 * @param string $email
	 * @param VerificationToken $token
	 * @return int
	 * @throws UserNotFoundException
	 * @throws \Throwable
	 */
	public function addVerificationTokenForUser(string $email, VerificationToken $token) : int {
		try {
			$srmUser = $this->getUserByEmail($email);
			// TODO: fix relationship saving
			$srmUser->verification_token_id = $token->id;
			$srmUser->save();
			return $srmUser->verification_token_id;
		}
		catch (UserNotFoundException $exception) {
			throw $exception;
		}
	}

	/**
	 * @param string $email
	 * @throws UserNotFoundException
	 * @throws \Throwable
	 */
	public function enableMfa(string $email) : void {
		try {
			$srmUser = $this->getUserByEmail($email);
			$srmUser->update(['has_mfa' => true]);
		}
		catch (UserNotFoundException | \Throwable $exception) {
			throw $exception;
		}
	}

    /**
     * @param string $email
     * @param string|null $secret
     * @return string
     * @throws UserNotFoundException
     * @throws \Throwable
     */
	public function setMfaSecret(string $email, ?string $secret) : ?string {
        $srmUser = $this->getUserByEmail($email);
	    try {
	        $srmUser->update(['otp_secret' => $secret]);
	        return $secret;
        }
        catch (UserNotFoundException | \Throwable $exception) {
	        throw $exception;
        }
    }

	/**
	 * @param string $email
	 * @throws UserNotFoundException
	 * @throws \Throwable
	 */
	public function disableMfa(string $email) : void {
		try {
			$srmUser = $this->getUserByEmail($email);
			$srmUser->update(['has_mfa' => false, 'otp_secret' => null]);
		}
		catch (UserNotFoundException | \Throwable $exception) {
			throw $exception;
		}
	}

    /**
     * @param int $numToReturn
     * @param bool $usePermission
     * @return LengthAwarePaginator
     */
    public function getUsers(int $numToReturn = 25, bool $usePermission = false) : LengthAwarePaginator {
        if($numToReturn > 0) {
            if ($usePermission){
                $role = Auth::user()->role;
                if ($role == USER::ROLE_ADMIN) {
                    $companyId = Auth::user()->company_id;
                    $userQuery = User::where('company_id', $companyId);
                    $users = $userQuery->paginate($numToReturn);
                    return $users;
                }
                return User::paginate($numToReturn);
            }
            return User::paginate($numToReturn);
        }

        return User::all();

    }

    /**
     * @param array $attributes
     * @return User
     */
    public function createUser(array $attributes, string $password = '', string $email = '') : User {
        $newUser = User::create($attributes);
        $combinedColl = collect($attributes);
        $combinedColl->put('message', 'Create User');
        $combinedColl->put('company_id', $attributes['company_id']);
        $message = view('emails.temppassword', ['password' => $password])->render();
        $subject =  'NeuDocs SRM: An account has been created for you';
        SendTempPasswordEmail::dispatch($password, $email, $message, $subject);
        event(new NeulogModelEvent($newUser, $combinedColl));
        return $newUser;
    }

    /**
     * @param string $email
     * @param bool $create
     * @throws NeuEntityExistsException
     */
    public function checkExistingEmail(string $email, bool $create) : void {
        $user = User::withTrashed()->whereRaw('lower(email) = ?', [strtolower($email)])->first();
        $text = $create ? 'create' : 'update';
        if ($user !== null){
            if ($user->is_deleted){
                throw new NeuEntityExistsException('Cannot ' . $text . ' user; a deleted user with this email address exists in the system. Please contact support.');
            }
            throw new NeuEntityExistsException('Cannot ' . $text .' user; a user with this email address already exists in the system.');
        }
    }



    public function findUser($id)
    {
        return User::find($id);
    }

    /**
     * @param string $id
     * @param array $attributes
     * @param bool $logUserUpdate
     * @throws \NeubusSrm\Lib\Exceptions\NeuSrmException
     */
    public function updateUser(string $id, array $attributes, bool $logUserUpdate = true) : void
    {
        if ($logUserUpdate) {
            $userToBeUpdated = $this->getUserById($id)->load('company:id,company_name');
            $combinedColl = collect($attributes);
            $combinedColl->put('message', 'Update User');
            $combinedColl->put('operation', 'update');
            $userEmail = $userToBeUpdated->email;
            $combinedColl->put('email', $userEmail);
            foreach ($attributes as $col => $attr) {
                if ($userToBeUpdated->{$col} !== $attr && $col !== 'password') {
                    $fieldStr = $this->getFieldString($col, $userToBeUpdated, $attr);
                    if ($col === 'company_id'){
                        $combinedColl->put('company_name', $fieldStr);
                    }
                    else {
                        $combinedColl->put($col, $fieldStr);
                    }
                } else {
                    if ($col === 'password') {
                        $combinedColl->put($col, ' changed ');
                    }
                    else if ($col !== 'email') {
                        $combinedColl->forget($col);
                    }
                }
            }
            if ($combinedColl->get('user_id') === null) {
                $combinedColl->put('user_id', $id);
            }
            $combinedColl->put('company_id', $attributes['company_id']);
            event(new NeulogModelEvent($userToBeUpdated, $combinedColl));
        }
        User::whereId( $id)->update($attributes);
    }

    /**
     * @param $col
     * @param $userToBeUpdated
     * @param $attr
     * @return string
     */
    public function getFieldString($col, $userToBeUpdated, $attr) : string
    {
        $fieldStr = sprintf('%s changed from %s to %s', $col, $userToBeUpdated->{$col}, $attr);
        if ($col === 'company_id') {
            $fieldStr = $this->getCompanyNamesForUpdates($userToBeUpdated, $attr) ?? '';
        }
        return $fieldStr;
    }

    /**
     * @param $userToBeUpdated
     * @return string
     */
    public function getFieldCompanyId($userToBeUpdated) : string
    {
        $userCompanyId = $userToBeUpdated->company_id;
        if (Auth::check()){
            $userCompanyId = Auth::user()->company_id;
        }
        return $userCompanyId;
    }

    /**
     * @param User $userBeingUpdated
     * @param string $newCompanyId
     * @return string|null
     */
    private function getCompanyNamesForUpdates(User $userBeingUpdated, string $newCompanyId) : ?string {
        $oldCompanyName = $userBeingUpdated->company->company_name;
        $newCompanyName = $userBeingUpdated->company::whereId($newCompanyId)
            ->first()->company_name;
        if ($oldCompanyName !== $newCompanyName) {
            $fieldStr = sprintf('%s changed from %s to %s', 'Company Name', $oldCompanyName, $newCompanyName);
            return $fieldStr;
        }
        return null;
    }

    /**
     * @param string $password
     */
    public function updateUserPassword(string $password) : void {
        $user = Auth::user();
        $user->password = bcrypt($password);
        $user->is_temp = false;
        $user->save();
    }

    /**
     * @param string $email
     * @param string $password
     */
    public function resetUserPasswordByEmail(string $email, string $password) : void {
        $user = User::where('email', $email)->first();
        throw_if($user == null,
            UserNotFoundException::class, 'No users with this email');
        $user->password = bcrypt($password);
        $user->is_temp = true;
        $user->save();
    }

    /**
     * TODO: the user is already retrieved, why retrieve it again to delete? $user->delete() should be just fine
     * @param $id
     * @return bool
     * @throws \NeubusSrm\Lib\Exceptions\NeuSrmException
     */
    public function deleteUser($id) : bool
    {
        $user = $this->getUserById($id);
        $logParams = collect([
            'message' => 'Delete User',
            'name' => $user->name,
            'email' => $user->email,
            'company_id' => $user->company_id
        ]);


        event(new NeulogModelEvent($user, $logParams, 'delete'));
        return User::whereId($id)->delete();
    }

    public function getUsersByCompanyId(string $companyId) : UsersCollection {

        // TODO: company relation itself may not be needed
        $users = User::whereCompanyId($companyId)->get();
        throw_if($users == null,
            UserNotFoundException::class, 'No users exist for this company');
        $usersColl = collect($users);


        return new UsersCollection($usersColl);
    }

    public  function getAllUsers(){
        return User::find(1);
    }

    /**
     * @param string $userId
     * @param string $projectId
     * @throws UserNotFoundException
     */
    public function updateDefaultProjectForUser(string $userId, string $projectId) : void {
        try {
            $srmUser = $this->findUser($userId);
            $srmUser->default_project_id = $projectId;
            $srmUser->save();
        }
        catch (UserNotFoundException $exception) {
            throw $exception;
        }
    }

    /**
     * @param string $userId
     * @return Carbon
     * @throws \NeubusSrm\Lib\Exceptions\NeuSrmException
     */
    public function getUserLastAccessTime(string $userId) : Carbon {
        $user = User::whereId($userId)->with(['session' => function($query) {
            $query->orderBy('last_activity', 'desc')->limit(1);
        }])->get()->first();
        neu_throw_if($user === null, UserNotFoundException::class, 'There is no user for that id');
        return Carbon::createFromTimestamp($user->session->last_activity);
    }

    /**
     * @param string $userId
     * @param array $attributes
     */
    public function updateUserProfile(string $userId, array $attributes) : void {
        try {
            User::where('id', '=', $userId)->update($attributes);
        }
        catch (UserNotFoundException $exception) {
            throw $exception;
        }
    }
    /**
     * @param string $keyword
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function searchQuery(string $keyword) : Builder {
        $query = User::query();
        $role = Auth::user()->role;
        if ($role == USER::ROLE_ADMIN) {
            $companyId = Auth::user()->company_id;
            $query = $query->where('company_id', $companyId);
        }
        if ($keyword != null && $keyword != '') {
            $query = $query->where(function ($subQuery) use ($keyword) {
                foreach (UserRepository::SEARCH_ARR as $key => $arr) {
                    if ($arr['type'] == 'nojoin') {
                        $subQuery = $subQuery->orWhere($arr['col'], 'ilike', '%' . $keyword . '%');
                    } else {
                        $subQuery = $subQuery->orWhereHas($arr['relation'], function ($queryHas) use ($arr, $keyword){
                            $queryHas->where($arr['col'], 'ilike', '%' . $keyword . '%');
                        });
                    }
                }
                return $subQuery;
            });
        }
        return $query;
    }

    /**
     * @param Builder $query
     * @param string $sortBy
     * @param string $order
     * @return Builder
     */
    public function orderQuery(Builder $query, string $sortBy, string $order) : Builder {
        if ($sortBy != null && $sortBy != ''){
            $arr = UserRepository::SEARCH_ARR[$sortBy];
            if ($arr['type'] == 'nojoin'){
                $query = $query->orderBy($arr['col'], $order);
            }
            else{
                $query = $query->leftJoin($arr['table'], $arr['foreignKey'], '=', $arr['localKey'])
                    ->orderBy($arr['table'] . '.' . $arr['col'], $order);
            }
        }
        return $query;
    }

    /**
     * @param Builder $query
     * @return array
     * @throws \Throwable
     */
    public function userSearch(Builder $query) : array {
        $results = $query->paginate(25);
        neu_throw_if($results == null || $results->isEmpty(),
            UserNotFoundException::class, 'There are no users for this query');
        return ['result' => new UserCollection($results->getCollection()), 'total' => $results->total()];
    }

    /**
     * @param string $userId
     * @return Collection
     * @throws \NeubusSrm\Lib\Exceptions\NeuSrmException
     */
    public function getLoggableParams(string $userId) : Collection {
        $user = $this->getUserById($userId);
        return collect([
            ['name' => 'name', 'value' => $user->name],
            ['name' => 'email', 'value' => $user->email]
        ]);
    }
}
