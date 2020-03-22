<?php

namespace NeubusSrm\Http\Controllers\api\v1\User;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use NeubusSrm\Events\NeulogModelEvent;
use NeubusSrm\Http\Controllers\api\v1\ApiController;
use NeubusSrm\Http\Requests\NeuSrmLoginRequest;
use NeubusSrm\Http\Requests\NeuSrmUserRequest;
use NeubusSrm\Http\Requests\ResetPasswordDoubleRequest;
use NeubusSrm\Http\Requests\ResetPasswordUserRequest;
use NeubusSrm\Http\Requests\SearchOrderRequest;
use NeubusSrm\Http\Requests\UpdateProfileRequest;
use NeubusSrm\Lib\Exceptions\NeuSrmException;
use NeubusSrm\Models\Auth\User;
use NeubusSrm\Services\UserService;
use NeubusSrm\Http\Requests\NeuSrmVerifyRequest;
use NeubusSrm\Lib\Constants\HttpConstants;
use NeubusSrm\Http\Resources\UserCollection;
use NeubusSrm\Lib\DataMappers\Formatter;
use NeubusSrm\Http\Requests\NeuSrmCreateUserRequest;
use Illuminate\Http\Request;

/**
 * Class UserApiController
 * @package NeubusSrm\Http\Controllers\api\v1\User
 */
class UserApiController extends ApiController
{

    /**
     * @var UserService
     */
    private $userService;

    /**
     * UserApiController constructor.
     * @param UserService $userService
     */
    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    /**
     * @param null $format
     * @return JsonResponse
     */
    public function all($format = null) {
        try {
            $users = $this->userService->getAllUsers();
            return $this->apiSuccess('Users retrieved', $users);
        }
        catch (NeuSrmException | \Throwable $exception) {
            if ($exception instanceof NeuSrmException) {
                return $this->apiErrorWithException($exception);
            }
            \Log::error($exception->getMessage());
            return $this->apiError('An internal error occurred, please try again later');
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $data = $this->userService->index();
            $result = new UserCollection($data);
            return $this->apiSuccess('users retrieved', ['result' => $result, 'total' => $data->total()]);
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return JsonResponse
     */
    public function store(NeuSrmCreateUserRequest $request) : JsonResponse {
        try {
            $arrRequest = $request->all();
            $result = $this->userService->createUser($arrRequest);
            return $this->apiSuccess('New User Successful',$result, HttpConstants::HTTP_CREATED);
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $result = $this->userService->read($id);
        return $result;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return JsonResponse
     */
    public function update(NeuSrmUserRequest $request, $id) : JsonResponse {
        try {
            $arrRequest = $request->all();
            $this->userService->updateUser($arrRequest,$id);
            return $this->apiSuccess('Update User Successful');
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        try{
            $this->userService->delete($user->id);
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
     * @return JsonResponse
     */
    public function search():JsonResponse{
        try {
            $keyword = request()->input('keyword');
            $results = $this->userService->searchUser($keyword);
            return $this->apiSuccess('Request Search Successful', ['results' => $results]);
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
     * @param string $companyId
     * @return JsonResponse
     */
    public function byCompanyId(string $companyId){
        // TODO: why is there no error handling here?
        $users = $this->userService->getUsersByCompanyId($companyId);
        return $this->apiSuccess('users retrieved', [$users]);
    }

    /**
     * @param NeuSrmUserRequest $request
     * @return JsonResponse
     */
    public function updateDefaultProject(Request $request) : JsonResponse {
        $userId = Auth::id();
        $validatedData = $request->validate([
            'projectId' => 'required'
        ]);
        $projectId = $request->projectId;
        try {
            $this->userService->updateDefaultProject($userId, $projectId);
            return $this->apiSuccess('Update User Default Project Successful');
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
     * @param ResetPasswordDoubleRequest $request
     * @return JsonResponse
     */
    public function resetPasswordDouble(ResetPasswordDoubleRequest $request){
        $response = null;
        try {
            $password = $request->input('password');
            $this->userService->updateUserPassword($password);
            $response = $this->apiSuccess('Password successfully reset');
        }
        catch (NeuSrmException $exception){
            $response = $this->apiErrorWithException($exception);
        }
        catch ( \Throwable $exception) {
            \Log::error($exception->getMessage());
            $response = $this->apiError('An internal error occurred, please try again later');
        }
        finally{
            return $response;
        }
    }

    /**
     * @param ResetPasswordUserRequest $request
     * @return JsonResponse|null
     */
    public function resetPasswordUser(ResetPasswordUserRequest $request){
        try {
            $email = $request->input('email');
            $this->userService->resetUserPassword($email);
            return $this->apiSuccess('Reset User Password Successful');
        }
        catch (NeuSrmException $exception){
            return $this->apiErrorWithException($exception);
        }
        catch ( \Throwable $exception) {
            \Log::error($exception->getMessage());
            return $this->apiError('An internal error occurred, please try again later');
        }
    }

    /**
     * @param UpdateProfileRequest $request
     * @return array|JsonResponse
     */
    public function updateProfile(UpdateProfileRequest $request)
    {
        /** @var User $user */
        $user = Auth::user();
        $response = null;
        $userId = $user->id;
        $userData = $request->only('name', 'email', 'password');

        try {
            $this->userService->updateUserProfile($userData, $userId);

            if ($this->userService->userNeedsLogout($user, $userData)) {
                $this->userService->logoutUser();
                $response = $this->apiSuccess('Update User Successful', ['redirect_to' => route('logout')]);
            } else {
                $response = $this->apiSuccess('Update User Successful');
            }
        }
        catch (NeuSrmException $exception) {
            $response = $this->apiErrorWithException($exception);
        }
        catch (\Throwable $exception) {
            $response = $this->apiError($exception->getMessage());
        }
        finally {
            return $response;
        }
    }

    /**
     * @param SearchOrderRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function userSearch(SearchOrderRequest $request) : JsonResponse{
        $response = null;
        try {
            $sortBy = $request->input('sortBy') ? $request->input('sortBy') : '';
            $order = $request->input('order');
            $keyWord = $request->input('keyword') ? $request->input('keyword') : '';
            $users = $this->userService->userSearch($sortBy, $order, $keyWord);
            $response = $this->apiSuccess('Users retrieved', ['result' => $users['result'], 'total' => $users['total']]);
        }
        catch (\Exception $e){
            return $this->apiError($e->getMessage());
        }
        catch (NeuSrmException $exception) {
            return $this->apiErrorWithException($exception);
        }
        catch (\Throwable $exception) {
            \Log::error($exception->getMessage());
            return $this->apiError('An internal error occurred, please try again later');
        }
        finally{
            return $response;
        }
    }
}

