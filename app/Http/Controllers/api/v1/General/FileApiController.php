<?php
/**
 * User: mlawson
 * Date: 2019-01-28
 * Time: 08:17
 */

namespace NeubusSrm\Http\Controllers\api\v1\General;


use Illuminate\Http\Request;
use NeubusSrm\Events\NeulogActionEvent;
use NeubusSrm\Http\Controllers\api\v1\ApiController;
use NeubusSrm\Http\Requests\DeletionReasonRequest;
use NeubusSrm\Http\Requests\UploadFileRequest;
use NeubusSrm\Lib\Constants\HttpConstants;
use NeubusSrm\Lib\Exceptions\NeuDataStoreException;
use NeubusSrm\Lib\Exceptions\NeuSrmException;
use NeubusSrm\Services\FileService;
use NeubusSrm\Services\PartService;
use NeubusSrm\Services\RequestService;
use NeubusSrm\Http\Requests\RequestDownloadRequest;

/**
 * Class FileApiController
 * @package NeubusSrm\Http\Controllers\api\v1\General
 */
class FileApiController extends ApiController
{

    /**
     * @var FileService
     */
    private $fileService;

    /**
     * @var PartService
     */
    private $partService;

    /**
     * @var RequestService
     */
    private $requestService;

    /**
     * FileApiController constructor.
     * @param FileService $fileService
     * @param PartService $partService
     * @param RequestService $requestService
     */
    public function __construct(FileService $fileService, PartService $partService, RequestService $requestService) {
        $this->fileService = $fileService;
        $this->partService = $partService;
        $this->requestService = $requestService;
    }

    public function upload(UploadFileRequest $uploadFileRequest) {
        // for now, just save a single file
        try {
            $metaData = json_decode($uploadFileRequest->input('file_meta_data'), true);
            $result = $this->fileService->saveUploadedFileByProject($uploadFileRequest->input('project_id'),
                $uploadFileRequest->file('file_data'),
                $metaData);
            $this->partService->requestStatus($metaData['part_id']);
            return $this->apiSuccess('File Uploaded', $result, HttpConstants::HTTP_CREATED);
        }
        catch (\Throwable $exception) {
            if ($exception instanceof NeuSrmException) {
                return $this->apiErrorWithException($exception);
            }
            // TODO: this will be handled by the global handler
            \Log::error($exception->getMessage());
            return $this->apiError('An internal error occurred, please try again later');
        }
    }

    /**
     * @param DeletionReasonRequest $deletionReasonRequest
     * @return \Illuminate\Http\JsonResponse|null
     */
    public function removeUpload(DeletionReasonRequest $deletionReasonRequest) {
        $response = null;
        try {
            $fileId = $deletionReasonRequest->input('fileId');
            $deletionReason = $deletionReasonRequest->input('deletion_reason');
            $result = $this->fileService->updateDeletionReasonFileByFileId($fileId, $deletionReason);
            if ($result) {
                $this->requestService->updateRequestAfterFileDeletion($fileId);
                $this->fileService->deleteFileByFileId($fileId);
                $response = $this->apiSuccess('File deleted');
            }
        } catch (\Throwable $exception) {
            if ($exception instanceof NeuSrmException) {
                $response = $this->apiErrorWithException($exception);
            } else {
                \Log::error(
                    'An internal error occurred, please try again later',
                    ['error' => $exception->getMessage()]
                );
                $response = $this->apiError('An internal error occurred, please try again later');
            }
        } finally {
            return $response;
        }
    }

    /**
     * @param RequestDownloadRequest $request
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function downloadZipFromRequest(RequestDownloadRequest $request){
        return response()->stream(function () use ($request){
            $requestId = $request->input('downloadId');
            $this->fileService->zipFileByRequest($requestId);
        });
    }

    /**
     * @param RequestDownloadRequest $request
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function downloadZipFromPart(RequestDownloadRequest $request){
        return response()->stream(function () use ($request){
            $partId = $request->input('downloadId');
            $requestName = $request->input('requestName');
            if ($requestName) {
                event(new NeulogActionEvent('Request Download', collect([
                    ['name' => 'Request Name',
                        'value' => $requestName]
                ])));
            }
            $this->fileService->zipFileByPart($partId, $requestName);
        });
    }

    /**
     * @param RequestDownloadRequest $request
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function downloadZipById(RequestDownloadRequest $request) {
        return response()->stream(function () use ($request){
            $fileId = $request->input('downloadId');
            $this->fileService->zipFileById($fileId);
        });
    }
}
