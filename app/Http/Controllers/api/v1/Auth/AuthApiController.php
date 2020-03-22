<?php
/**
 * User: mlawson
 * Date: 11/12/18
 * Time: 12:27 PM
 */

namespace NeubusSrm\Http\Controllers\api\v1\Auth;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use NeubusSrm\Http\Controllers\api\v1\ApiController;
use NeubusSrm\Http\Requests\NeuSrmLoginRequest;
use NeubusSrm\Http\Requests\UniqueUserRequest;
use NeubusSrm\Http\Requests\VerifyOtpRequest;
use NeubusSrm\Lib\Exceptions\NeuSrmException;
use NeubusSrm\Lib\Exceptions\UserNotFoundException;
use NeubusSrm\Rules\VerifyOtp;
use NeubusSrm\Services\UserService;
use NeubusSrm\Http\Requests\NeuSrmVerifyRequest;
use NeubusSrm\Lib\Constants\HttpConstants;
use Gate;

/**
 * Class AuthApiController
 * @package NeubusSrm\Http\Controllers\api\v1\Auth
 */
class AuthApiController extends ApiController
{

	use AuthenticatesUsers;

	/**
	 * @var UserService
	 */
	private $userService;

	/**
	 * AuthApiController constructor.
	 * @param UserService $userService
	 */
	public function __construct(UserService $userService) {
		$this->userService = $userService;
	}

	/**
	 * @param NeuSrmLoginRequest $neuSrmLoginRequest
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function loginViaApi(NeuSrmLoginRequest $neuSrmLoginRequest) : JsonResponse {
	    $response = null;
		try {
			$hasMfa = $this->userService->userHasMfa($neuSrmLoginRequest->input('email'));
			$mfaSetup = $this->userService->userMfaNeedsSetup($neuSrmLoginRequest->input('email'));
			$publicToken = $this->userService->loginUser($neuSrmLoginRequest->input('email'),
				$neuSrmLoginRequest->input('password'), $hasMfa);
			$returnData = ['verify_token' => $publicToken];
			$returnData['has_mfa'] = $hasMfa;
			$returnData['mfa_setup'] = $mfaSetup['needs_setup'];

			$returnData['mfa_setup_uri'] = $mfaSetup['mfa_setup_uri'] ?? '';
			$returnData['mfa_verify_uri'] = $mfaSetup['mfa_verify_uri'] ?? '';

			$response = $this->apiSuccess('Verifying login...', $returnData);

		} catch (NeuSrmException $exception) {
            \Log::error($exception->getMessage());
			$response = $this->apiErrorWithException($exception);
		}
		catch (\Throwable $exception) {
            \Log::error($exception->getMessage());
            $response = $this->apiError('An internal error occurred, please try again later');
        }
        finally {
		    return $response;
        }
	}

    /**
	 * @param NeuSrmVerifyRequest $neuSrmVerifyRequest
	 * @return \Illuminate\Http\JsonResponse
	 * @throws \Throwable
	 */
	public function verifyLogin(NeuSrmVerifyRequest $neuSrmVerifyRequest) {
		if ($this->userService->verifyUser($neuSrmVerifyRequest->input('email'),
			$neuSrmVerifyRequest->input('verify_token'))) {
			$routeToUse = $this->userService->getDefaultRoute();
			return $this->apiSuccess('verified!', ['to' => $routeToUse,
				 'mfa_uri' => route('login.do.token')]);
		}
		$this->userService->logoutUser(); // end the session completely
        abort(HttpConstants::HTTP_FORBIDDEN);
	}

    /**
     * @param VerifyOtpRequest $verifyOtpRequest
     * @return \Illuminate\Http\JsonResponse
     */
	public function finishLoginWithOtp(VerifyOtpRequest $verifyOtpRequest) {
		$routeToUse = $this->userService->getDefaultRoute();
		// if we made it here, it's been verified
		return $this->apiSuccess('OTP verified', ['redirect_to' => $routeToUse]);
	}

    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \NeubusSrm\Lib\Exceptions\NeuEntityNotFoundException
     * @throws \Throwable
     */
	public function getMfaQrCode() : JsonResponse {
	    // set the secret
        $altInput = request()->input('alt');
        $alt = !$altInput || $altInput == 'false' ? false : true;
        $mfaSetupResult = $this->userService->setupUserMfa(request()->input('email'), $alt);
        // return the image
        try {
            return $this->apiSuccess('MFA enabled',
                ['qr_img' => 'data:image/png;base64,'.$mfaSetupResult['encoded_qr'],
                    'verification_token' => $mfaSetupResult['verification_token'], 'secret' => $mfaSetupResult['secret']]);
        }
        catch (NeuSrmException $exception) {
            return $this->apiErrorWithException($exception);
        }
        catch (\Throwable $exception) {
            \Log::error($exception->getMessage());
            return $this->apiError('An internal error occurred, please try again later');
        }
        // user can scan image
    }

    /**
     * @param VerifyOtpRequest $verifyOtpRequest
     * @return JsonResponse
     * @throws \Throwable
     */
    public function verifyMfaSetup(VerifyOtpRequest $verifyOtpRequest) : JsonResponse {
        try {
            if ($verifyOtpRequest->input('confirm', false)) {
                $this->userService->setUserOtpIsVerfied($verifyOtpRequest->input('email'), true);
            }
            return $this->apiSuccess('verified', ['redirect_to' => route('logout')]);
        }
        catch (NeuSrmException $exception) {
            return $this->apiErrorWithException($exception);
        }
        catch (\Throwable $exception) {
            \Log::error($exception->getMessage());
            return $this->apiError('An internal error occurred, please try again later');
        }
    }

    /**
     * @param NeuSrmVerifyRequest $neuSrmVerifyRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancelMfaSetup(NeuSrmVerifyRequest $neuSrmVerifyRequest) : JsonResponse {
        try {
            $redirectTo = $this->userService->resetUserMfaSetup($neuSrmVerifyRequest->input('email'),
                $neuSrmVerifyRequest->input('verify_token'));
            return $this->apiSuccess('MFA Setup Cancelled', ['redirect_to' => $redirectTo]);
        }
        catch (NeuSrmException $exception) {
            return $this->apiErrorWithException($exception);
        }
        catch (\Throwable $exception) {
            \Log::error($exception->getMessage());
            return $this->apiError('An internal error occurred, please try again later');
        }
    }

    /**
     * @param VerifyOtpRequest $verifyOtpRequest
     * @return JsonResponse
     */
    public function refreshMfaSession(VerifyOtpRequest $verifyOtpRequest): JsonResponse {
        // making it here means the token was valid, now to reset their timeout
        $responseObject = null;
        try {
            // once the mfa session is verified, we can cancel out the verification step and be done
            $responseObject = $this->apiSuccess('Session token refreshed',
                ['redirect_to' => $this->userService->getDefaultRoute()]);

        } catch (UserNotFoundException $exception) {
            $responseObject = $this->apiErrorWithException($exception);

        } catch (\Throwable $exception) {
            \Log::error($exception->getMessage());
            $responseObject = $this->apiError('An internal error occurred, please try again later');
        } finally {
            return $responseObject;
        }
    }
}
