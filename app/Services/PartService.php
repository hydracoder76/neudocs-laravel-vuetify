<?php
/**
 * User: mlawson
 * Date: 2018-12-04
 * Time: 14:18
 */

namespace NeubusSrm\Services;

use NeubusSrm\Events\NeulogActionEvent;
use NeubusSrm\Lib\Exceptions\NeuSrmException;
use NeubusSrm\Models\Indexing\FileUpload;
use NeubusSrm\Models\Indexing\Part;
use NeubusSrm\Models\Org\Request;
use NeubusSrm\Repositories\PartRepository;

/**
 * Class PartService
 * @package NeubusSrm\Services
 */
class PartService extends NeuSrmService
{

	/**
	 * @var PartRepository
	 */
	private $partRepository;

	/**
	 * PartService constructor.
	 * @param PartRepository $partRepository
	 */
	public function __construct(PartRepository $partRepository) {
		$this->partRepository = $partRepository;
	}

	/**
	 * @param string $projectId
	 * @return array
	 * @throws NeuSrmException
	 * @throws \Throwable
	 */
	public function getAllIndexesForDisplay(string $projectId) : array {
		try {
			$partsColl = $this->partRepository->getPartsByProjectId($projectId);
			return $partsColl->load(['indexes', 'updatedBy'])->map(function(Part $partsWithIndexes) {
				return collect(['index_name' => $partsWithIndexes->part_name,
					'updated_by' => $partsWithIndexes->updatedBy->name,
					'indexes' => $partsWithIndexes->indexes->load('type')]);
			})->toArray();
		}
		catch (NeuSrmException | \Throwable $exception) {
			throw $exception;
		}
	}

    /**
     * @param array $partData
     * @return array
     */
	public function getPartFiles(array $partData, array $requestParts) : array{
	    $parts = $this->partRepository->getPartFiles($partData);
	    return $parts->map(function(Part $part)use ($requestParts){
	        $requests = implode(',', array_column($part->requests->toArray(), 'request_name'));
	        $files = $part->files->map(function(FileUpload $file){
                return ['file_name' => $file->true_file_name, 'file_type' => 'pdf',
                    'file_download_url' => $file->current_fs_location, 'file_id' => $file->id];
            });
            
            $collection = [
                'box_name' => $part->box->box_name,
                'part_name' => $part->part_name,
                'requests' => $requests,
                'files' => $files,
                'part_id' => $part->id                
            ];

            foreach ($requestParts as $item){
                if($item->part_id == $part->id){
                    $collection['request_id'] = $item->request_id;
                }
            }

	       return collect($collection);
        })->toArray();
    }

    /**
     * @param string $partID
     */
    public function requestStatus(string $partID){
	    $this->partRepository->markUploaded($partID);
        $query = $this->partRepository->partRequestProgress($partID);
        $requests = $this->partRepository->partRequestFulfill($query);
    }

    public function fulfillPart(string $partID, array $dataAddChecked){
        $this->partRepository->fulfillPart($partID, $dataAddChecked);
    }

    /**
     * @param string $partID
     * @return mixed
     */ 
    public function partMediaType(string $partID) : Part
    {
        return $this->partRepository->getPartById($partID);
    }
}
