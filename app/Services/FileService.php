<?php
/**
 * User: mlawson
 * Date: 2019-01-28
 * Time: 10:51
 */

namespace NeubusSrm\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;

use Mockery\Exception;
use NeubusSrm\Lib\Builders\FileSystem\Drivers\FilePathDriver;
use NeubusSrm\Lib\Builders\FileSystem\FilePath;
use NeubusSrm\Lib\Builders\FileSystem\PathBinders\PathBinder;
use NeubusSrm\Lib\Exceptions\NeuDataStoreException;
use NeubusSrm\Lib\Exceptions\NeuEntityNotFoundException;
use NeubusSrm\Lib\Exceptions\NeuFileException;
use NeubusSrm\Lib\Wrappers\Collections\FileUploadCollection;
use NeubusSrm\Models\Indexing\FileUpload;
use NeubusSrm\Repositories\FileUploadRepository;
use Storage;
use ZipStream\ZipStream;
use ZipStream\Option\Archive;
use ZipStream\Option\Method;
use NeubusSrm\Events\NeulogActionEvent;

/**
 * Class FileService
 * @package NeubusSrm\Services
 */
class FileService extends NeuSrmService
{
    /**
     * @var FilePath
     */
    private $filePath;

    /**
     * @var FileUploadRepository
     */
    private $fileUploadRepo;

    /**
     * @var \Illuminate\Filesystem\FilesystemAdapter
     */
    private $localDisk;

    /**
     * @var integer
     */
    const DEFLATE_FILE_SIZE = 3000000000;

    /**
     * FileService constructor.
     * @param FilePathDriver $filePathDriver
     * @param FileUploadRepository $fileUploadRepository
     */
    public function __construct(FilePathDriver $filePathDriver, FileUploadRepository $fileUploadRepository) {

        // when we need to create the path, just pass the binder in to create the locator once the data
        // is available
        $this->filePath = $filePathDriver;
        $this->fileUploadRepo = $fileUploadRepository;

        $this->localDisk = Storage::disk('srm');

    }

    /**
     * @param string $fileId
     * @throws \Throwable
     */
    public function deleteFileByFileId(string $fileId) : void {
        $fileEntityToUse = $this->fileUploadRepo->getById($fileId);
            // delete from fs
        throw_unless($this->fileDestroy($fileEntityToUse),
            NeuDataStoreException::class, 'Could not delete file with ID: ' . $fileId);
    }

    /**
     * @param string $pathToCheck
     * @return bool whether or not the file exists
     */
    public function verifyFileExistence(string $pathToCheck) : bool {
        return $this->localDisk->exists($pathToCheck);
    }



    /**
     * @param string $projectId
     * @param UploadedFile $uploadedFile
     * @param array $extra
     * @return string
     * @throws \NeubusSrm\Lib\Exceptions\NeuDataStoreException
     * @throws \Throwable
     */
    public function saveUploadedFileByProject(string $projectId, UploadedFile $uploadedFile, array $extra = []) : array {
        // for NeuSrm, we're going to bind the binder from the srm config file, which is already injected
        // and we're going to pass in he required data to that binder to create its locator. in this
        // case we're passing in the project id and filename.

        $finalName = $this->setStorageFilename($uploadedFile);

        $fileData = $this->fileUploadRepo->saveFileMeta([
            'box_number' => $extra['box_number'] ?? '',
            'file_mime' => $extra['file_mime'],
            'part_name' => $extra['part_name'] ?? '',
            'part_id' => $extra['part_id'] ?? '',
            'uploaded_by' => $extra['user_id'] ?? \Auth::id(),
            'true_file_name' =>$extra['file_name'],
            'hashed_file_name' => $finalName,
            'is_scanned' => $extra['is_scanned'] ?? false
        ]);
        $pathParams = [
            'base_dir' => config('srm.file_storage.local.dir'),
            'project_id' => $projectId,
            'file_id' => $fileData->id
        ];
        $finalLocation = $this->fileOut($pathParams, $uploadedFile, $finalName);
        $this->fileUploadRepo->updateFileMeta($fileData->id, ['current_fs_location' => $finalLocation]);
        return ['file_id' => $fileData->id];

    }

    /**
     * @param string $requestId
     * @throws NeuFileException
     */
    public function zipFileByRequest(string $requestId) : void {
        $files = $this->fileUploadRepo->getFilesByRequestId($requestId);
        $zipFileName = $this->zipFiles($files);
        $requestName = $files->first()->part->requests->first()->request_name;
        $params = collect([['name' => 'Request Number', 'value' => $requestName . ' '], ['name' => 'Zip File Name', 'value' => $zipFileName ]]);
        event(new NeulogActionEvent('Download File', $params));
    }

    /**
     * @param string $partId
     * @throws NeuFileException
     */
    public function zipFileByPart(string $partId, string $requestName) : void {
        $files = $this->fileUploadRepo->getFilesByPartId($partId);
        $zipFileName = $this->zipFiles($files);
        $params = collect([['name' => 'Request Number', 'value' => $requestName . ' '], ['name' => 'Zip File Name', 'value' => $zipFileName ]]);
        event(new NeulogActionEvent('Download File', $params));
    }

    /**
     * @param string $id
     * @throws NeuFileException
     */
    public function zipFileById(string $id): void {
        $files = $this->fileUploadRepo->getFilesByIdAndWhereHasPartRequest($id);
        $zipFileName = $this->zipFiles($files);
        $requestName = $files->first()->part->requests->first()->request_name;
        $params = collect([['name' => 'Request Number', 'value' => $requestName . ' '], ['name' => 'Zip File Name', 'value' => $zipFileName ]]);
        event(new NeulogActionEvent('Download File', $params));
    }

    /**
     * @param FileUploadCollection $files
     * @return string
     * @throws NeuFileException
     */
    public function zipFiles(FileUploadCollection $files) : string {
        $zipFileName = getrandmax() . '_' . time() . '.zip';
        try {
            $opt = new Archive();
            $opt->setSendHttpHeaders(true);
            $opt->setContentType('application/octet-stream');
            $manualFulfill = false;
            foreach ($files as $file) {
                $absolute = '/';
                if ($file->current_fs_location === config('srm.fulfillment_placeholder_file_location')){
                    $absolute = '';
                }
                if (filesize($absolute . $file->current_fs_location) > self::DEFLATE_FILE_SIZE) {
                    $opt->setLargeFileSize(0);
                    $opt->setLargeFileMethod(Method::DEFLATE());
                    $opt->setEnableZip64(true);
                    break;
                }
            }
            $zip = new ZipStream($zipFileName, $opt);
            $nameArr = [];
            foreach ($files as $file) {
                $absolute = config('filesystems.disks.srm.root');
                if ($file->current_fs_location === config('srm.fulfillment_placeholder_file_location')){
                    if ($manualFulfill){
                        continue;
                    }
                    $absolute = '';
                    $name = 'Fulfillment_Placeholder_File.pdf';
                    $manualFulfill = true;
                }
                else{
                    $name = $this->checkFileNameDuplicate($file->true_file_name, $nameArr);
                }
                $zip->addFileFromPath($name, $absolute . $file->current_fs_location);
            }
        }
        catch(Exception $e){
            throw new NeuFileException('File error: ' + $e->getMessage());
        }
        $zip->finish();
        return $zipFileName;
    }

    /**
     * @param string $name
     * @param array $nameArr
     * @return string
     */
    protected function checkFileNameDuplicate(string $name, array &$nameArr) : string{
        if (array_key_exists($name, $nameArr)){
            $nameArr[$name] += 1;
            $pathName = pathinfo($name);
            return $pathName['filename'] . '_' . $nameArr[$name] . '.' . $pathName['extension'];
        }
        $nameArr[$name] = 0;
        return $name;
    }


    /**
     * @param UploadedFile $uploadedFile
     * @return string
     */
    protected function setStorageFilename(UploadedFile $uploadedFile) : string {
        // from here we can grab the name to use and return it, append it to the path above. and done
        // in separate method in case we need to modify the naming logic
        return $uploadedFile->hashName();

    }

    /**
     * @param array $pathParams
     * @param UploadedFile $uploadedFile
     * @param string $hashedName
     * @return string the final resting place of the file
     * @throws \Throwable|NeuDataStoreException
     */
    protected function fileOut(array $pathParams, UploadedFile $uploadedFile, string $hashedName) : string {

        $finalPath = $this->filePath->createLocator($pathParams, resolve(PathBinder::class));

        $result = $this->localDisk->putFileAs($finalPath, $uploadedFile, $hashedName);
        throw_if($result === false,
            NeuDataStoreException::class, 'Unable to store uploaded file');

        return $result;
    }

    /**
     * @param FileUpload $fileUpload
     * @return bool
     * @throws NeuDataStoreException
     */
    protected function fileDestroy(FileUpload $fileUpload) : bool {
        return $this->fileUploadRepo->deleteFile($fileUpload->id);
    }

    /**
     * @param $fileId
     * @param $deletionReason
     * @return bool
     */
    public function updateDeletionReasonFileByFileId($fileId, $deletionReason) : bool {
        $deletionReasonData = ['deletion_reason' => $deletionReason];
        return $this->fileUploadRepo->updateDeletionReasonFile($fileId, $deletionReasonData);
    }
}
