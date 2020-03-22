<?php
/**
 * User: mlawson
 * Date: 2019-01-27
 * Time: 13:09
 */

namespace NeubusSrm\Repositories;

use function GuzzleHttp\Psr7\get_message_body_summary;
use NeubusSrm\Events\NeulogModelEvent;
use NeubusSrm\Lib\Exceptions\NeuDataStoreException;
use NeubusSrm\Lib\Exceptions\NeuEntityNotFoundException;
use NeubusSrm\Lib\Wrappers\Collections\FileUploadCollection;
use NeubusSrm\Models\Indexing\FileUpload;
use NeubusSrm\Lib\Traits\WorksWithModels;
use NeubusSrm\Models\Org\Request;
use NeubusSrm\Models\Relational\RequestPart;
use ZipStream\File;

/**
 * Class FileUploadRepository
 * @package NeubusSrm\Repositories
 */
class FileUploadRepository implements NeuSrmRepository
{

    use WorksWithModels;

    /**
     * @return string
     */
    public function getModelClass(): string {
        return FileUpload::class;
    }


    /**
     * @param string $userId
     * @return FileUploadCollection
     * @throws \Throwable|NeuEntityNotFoundException
     */
    public function getFilesUploadedByUser(string $userId) : FileUploadCollection {
        $files = FileUpload::with(['uploadedBy', function($query) use ($userId) {
            $query->where('id', '=', $userId)->orderBy(FileUpload::CREATED_AT, 'desc');
        }])->get();

        throw_if($files->count() == 0,
            NeuEntityNotFoundException::class, 'No files uploaded for this user');

        return $files;
    }

    /**
     * @param array $fileData
     * @return FileUpload
     * @throws \Throwable|NeuDataStoreException
     */
    public function saveFileMeta(array $fileData) : FileUpload {
        $newFile = FileUpload::create([
            'box_number' => $fileData['box_number'],
            'part_name' => $fileData['part_name'],
            'part_id' => $fileData['part_id'],
            'uploaded_by' => $fileData['uploaded_by'],
            'true_file_name' => $fileData['true_file_name'],
            'hashed_file_name' => $fileData['hashed_file_name'],
            'file_mime' => $fileData['file_mime'],
            'current_fs_location' => $fileData['current_fs_location'] ?? '',
            'is_scanned' => $fileData['is_scanned']
        ]);

        neu_throw_if($newFile === null, NeuDataStoreException::class, 'Could not create new file row');
        $params = collect([
            'message' => 'File Uploaded',
            'file_name' => $fileData['true_file_name'],
            'box_number' => $fileData['box_number'],
            'part_name' => $fileData['part_name'],
            'upload_method' => $fileData['is_scanned'] ? 'scan' : 'direct',
            'company_id' => \Auth::user()->company_id
        ]);
        event(new NeulogModelEvent($newFile, $params));
        return $newFile;
    }

    /**
     * @param string $fileId
     * @param array $metaData
     * @return FileUpload
     * @throws \Throwable|NeuEntityNotFoundException|NeuDataStoreException
     */
    public function updateFileMeta(string $fileId, array $metaData) : FileUpload {
        $fileUploadEntity = FileUpload::whereId($fileId)->first();
        throw_if($fileUploadEntity->count() == 0, NeuEntityNotFoundException::class, 'This file does not exist');
        $result = $fileUploadEntity->update($metaData);
        throw_unless($result, NeuDataStoreException::class, 'Unable to update file meta data');
        return $fileUploadEntity;
    }

    /**
     * @param string $fileId
     * @return bool
     * @throws NeuDataStoreException
     */
    public function deleteFile(string $fileId) : bool {
        try {
            return FileUpload::whereId($fileId)->delete();
        }
        catch(\Exception $exception) {
            throw new NeuDataStoreException('Unable to delete file: ' . $exception->getMessage());
        }

    }


    /**
     * @param string $requestId
     * @return FileUploadCollection
     */
    public function getFilesByRequestId(string $requestId) : FileUploadCollection{
        $callback = function($query) use ($requestId) {
            $query->where('id', $requestId);
        };
        $result = FileUpload::whereHas('part.requests', $callback)->with(['part.requests' => $callback])->get();

        return $result;
    }

    /**
     * @param string $partId
     * @return FileUploadCollection
     */
    public function getFilesByPartId(string $partId) : FileUploadCollection{
        $result = FileUpload::where('part_id', $partId)->get();
        return $result;
    }

    /**
     * @param string $id
     * @return \NeubusSrm\Lib\Wrappers\Collections\FileUploadCollection
     */
    public function getFilesById(string $id): FileUploadCollection {
        $result = FileUpload::where('id', $id)
            ->get();

        return $result;
    }

    /**
     * @param string $id
     * @return \NeubusSrm\Lib\Wrappers\Collections\FileUploadCollection
     */
    public function getFilesByIdAndWhereHasPartRequest(string $id) : FileUploadCollection {
        $result = FileUpload::whereId($id)
            ->get();

        $partId = $result[0]->part_id;

        $callback = function($query) use($partId) {
            $query->where('part_id_ref', $partId);
        };
        $resultRequest = FileUpload::where('part_id', $partId)
            ->whereHas('part.requests', $callback)->with(['part.requests' => $callback])
            ->get();

        return $resultRequest;
    }

    /**
     * @param string $fileId
     * @param array $deletionReasonData
     * @return bool
     * @throws NeuDataStoreException
     * @throws \Throwable
     */
    public function updateDeletionReasonFile(string $fileId, array $deletionReasonData) : bool {
        try {
            $fileUploadEntity = FileUpload::whereId($fileId)->first();
            throw_if($fileUploadEntity->count() == 0, NeuEntityNotFoundException::class, 'This file does not exist');
            $result = $fileUploadEntity->update($deletionReasonData);
            $params = collect([
                'message' => 'File Deleted',
                'file_name' => $fileUploadEntity->true_file_name,
                'box_number' => $fileUploadEntity->box_number,
                'part_name' => $fileUploadEntity->part_name,
                'upload_method' => $fileUploadEntity->is_scanned ? 'scan' : 'direct',
                'reason' => $deletionReasonData['deletion_reason'],
                'company_id' => \Auth::user()->company_id
            ]);
            event(new NeulogModelEvent($fileUploadEntity, $params, 'delete'));
            return $result;
        } catch(\Exception $exception) {
            throw new NeuDataStoreException('Unable to update deletion reason: ' . $exception->getMessage());
        }
    }
}
