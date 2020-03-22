<?php

namespace NeubusSrm\Http\Controllers\api\v1\Company;


use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use NeubusSrm\Http\Controllers\api\v1\ApiController;
use NeubusSrm\Http\Requests\SearchOrderRequest;
use NeubusSrm\Lib\Constants\HttpConstants;
use NeubusSrm\Lib\DataMappers\Formatter;
use NeubusSrm\Lib\Exceptions\NeuSrmException;
use NeubusSrm\Services\CompanyService;
use NeubusSrm\Models\Org\Company;
use NeubusSrm\Http\Requests\CompanyRequest;

/**
 * Class CompaniesApiController
 * @package NeubusSrm\Http\Controllers\api\v1\Company
 */
class CompaniesApiController extends ApiController
{

	/**
	 * @var CompanyService
	 */
    private $companyService;

	/**
	 * CompaniesApiController constructor.
	 * @param CompanyService $companyService
	 */
    public function __construct(CompanyService $companyService) {
        $this->companyService = $companyService;
    }

    /**
     * @param null $format
     * @return \Illuminate\Http\JsonResponse
     */
    public function all($format = null){
        try {
            $data = $this->companyService->all();
            return $this->apiSuccess('companies retrieved', [$data]);
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
	 * @return \Illuminate\Http\JsonResponse
	 */
    public function index()
    {
        try {
            $data = $this->companyService->index();
            return $this->apiSuccess('companies retrieved', [$data]);
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
	 * @param CompanyRequest $request
	 * @return \Illuminate\Http\JsonResponse
	 */
    public function store(CompanyRequest $request) : JsonResponse {
        try {
            $arrRequest = $request->all();
            $result = $this->companyService->createCompany($arrRequest);
            return $this->apiSuccess('New Company Successful',['company_id' => $result], HttpConstants::HTTP_CREATED);
        }
        catch (NeuSrmException $exception) {
            return $this->apiErrorWithException($exception);
        }
    }

	/**
	 * @param $id
	 * @return \Illuminate\Http\JsonResponse
	 */
    public function show($id)
    {
        try {
            $result = $this->companyService->read($id);
            return $this->apiSuccess('Show Company Successful',['company' => $result]);
        }
        catch (NeuSrmException | \Throwable $exception) {
            return $this->apiError($exception);
        }
    }

	/**
	 * @param CompanyRequest $request
	 * @param $id
	 * @return \Illuminate\Http\JsonResponse
	 */
    public function update(CompanyRequest $request, $id)
    {
        try {
            $arrRequest = $request->all();
            $this->companyService->updateCompany($arrRequest,$id);
            return $this->apiSuccess('Update Company Successful');
        }
        catch (NeuSrmException | \Throwable $exception) {
            return $this->apiError($exception);
        }
    }

	/**
	 * @param string $id a uuid
	 * @return \Illuminate\Http\JsonResponse
	 */
    public function destroy($id)
    {
        try{
            $this->companyService->deleteCompany($id);
        }
        catch (NeuSrmException | \Throwable $exception) {
            return $this->apiError($exception);
        }
    }

    /**
     * @param SearchOrderRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function companySearch(SearchOrderRequest $request) : JsonResponse{
        try {
            $sortBy = $request->input('sortBy') ? $request->input('sortBy') : '';
            $order = $request->input('order');
            $keyWord = $request->input('keyword') ? $request->input('keyword') : '';
            $projects = $this->companyService->companySearch($sortBy, $order, $keyWord);
            return $this->apiSuccess('Companies retrieved', ['result' => $projects['result'], 'total' => $projects['total']]);
        }
        catch (NeuSrmException $exception) {
            return $this->apiErrorWithException($exception);
        }
        catch (\Throwable $exception) {
            \Log::error($exception->getMessage());
            return $this->apiError('An internal error occurred, please try again later');
        }
    }
}
