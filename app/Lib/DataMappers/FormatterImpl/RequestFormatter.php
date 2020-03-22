<?php

namespace NeubusSrm\Lib\DataMappers\FormatterImpl;



use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use NeubusSrm\Lib\DataMappers\Formatter;
use NeubusSrm\Lib\Exceptions\NeuInvalidWrapperException;
use NeubusSrm\Lib\Wrappers\Collections\IndexTypesCollection;
use NeubusSrm\Lib\Wrappers\Collections\PartsCollection;
use NeubusSrm\Lib\Wrappers\Collections\RequestPartsCollection;
use NeubusSrm\Models\Indexing\Part;
use NeubusSrm\Models\Org\MediaType;
use NeubusSrm\Models\Org\Request;
use NeubusSrm\Models\Relational\RequestPart;
use NeubusSrm\Services\MediaTypeService;
use NeubusSrm\Services\ProjectService;
use NeubusSrm\Services\RequestService;
use Prophecy\Exception\Doubler\ClassNotFoundException;
use Illuminate\Database\Eloquent\Collection;
use Auth;

/**
 * Class RequestFormatter
 * @package NeubusSrm\Lib\DataMappers\FormatterImpl
 */
class RequestFormatter implements Formatter
{
    /**
     * @var MediaType
     */
    private $mediaTypeService;

    /**
     * RequestFormatter constructor.
     * @param MediaTypeService $mediaTypeService
     */
    public function __construct(MediaTypeService $mediaTypeService) {
        $this->mediaTypeService = $mediaTypeService;
    }

    /**
     * @param Collection $data
     * @param int $mode
     * @return array
     * @throws NeuInvalidWrapperException
     */
    public function format(Collection $data, int $mode, IndexTypesCollection $indexTypes = null): array {
        switch ($mode) {
            case ($mode == Formatter::MODE_TODO) && $data->whereInstanceOf(RequestPartsCollection::class):
                return $this->todoFormat($data);
            case ($mode == Formatter::MODE_COMPLETE) && $data->whereInstanceOf(RequestPartsCollection::class):
                return $this->completedFormat($data);
            case ($mode == Formatter::MODE_PARTS) && $data->whereInstanceOf(PartsCollection::class):
                return $this->partFormat($data, $indexTypes);
            default:
                throw new NeuInvalidWrapperException('No wrapper found for this collection type');
        }
    }

    /**
     * @param Request $request
     * @param Part $part
     * @param RequestPart $requestPart
     * @return array
     */
    protected function pageFormat(Request $request, Part $part, RequestPart $requestPart) : array{
        if (isset($part->box)) {
            $location = $part->box->box_location_code;
            $boxName = $part->box->box_name;
        }
        else{
            $location = '';
            $boxName = '';
        }
        $projectName = isset($request->project) ? $request->project->project_name : '';
        return ['project'=>$projectName, 'request'=>$request->request_name, 'location'=>$location,
            'box'=>$boxName, 'part'=>$part->part_name,'part_id'=>$requestPart->part_id_ref,
            'request_id'=>$requestPart->request_id_ref, 'external_comment' => $request->external_comment];
    }

    /**
     * @param Collection $data
     * @return array
     */
    protected function todoFormat(Collection $data) : array{
        $requestPartArray = [];
        $requestParts = $data;
        foreach($requestParts as $requestPart){
            $request = $requestPart->request;
            $part = $requestPart->part;
            $requestArray = $this->pageFormat($request, $part, $requestPart);
            $requestArray = $this->todoFormatData($requestArray, $request, $requestPart, $part);
            $requestPartArray[] = $requestArray;
        }
        return $requestPartArray;
    }

    /**
     * @param Collection $data
     * @return array
     */
    protected function completedFormat(Collection $data) : array{
        $requestPartArray = [];
        $requestParts = $data;
        foreach($requestParts as $requestPart){
            $request = $requestPart->request;
            $part = $requestPart->part;
            $requestArray = $this->pageFormat($request, $part, $requestPart);
            $requestArray = $this->completedFormatData($requestArray, $part, $request);
            $requestPartArray[] = $requestArray;
        }
        return $requestPartArray;
    }

    /**
     * @param array $requestArray
     * @param Request $request
     * @param RequestPart $requestPart
     * @param Part $part
     * @return array
     */
    protected function todoFormatData(array $requestArray, Request $request, RequestPart $requestPart, Part $part) : array {
        $requestedAt = $request->created_at->format('Y-m-d g:i A');
        $lock = $requestPart->lockedBy;
        $lockBool = $lock !== null;
        $mediaTypes = $part->mediaTypes;
        
        if ($lockBool) {
            $requestArray['being_used_by'] = $lockBool ? $lock->name : '';
        }
        $user = Auth::user();
        if ($lockBool && $user->id == $lock->id) {
            $lockBool = false;
        }
        $appendArray = [
            'requested_at' => $requestedAt,
            'select' => false,
            'disabled_select' => !$lockBool,
            'active_color' => ($lockBool ? 'gray' : 'black'),
            'completion' => ($request->is_in_process ? 'partial' : 'blank'),
            'request_status' => '',
            'request_review' => $request->comment,
            'filename' => $this->getFileUploads($part),
            'media_type_id' => [],
        ];

        $allMediaType = $this->mediaTypeService->allMediaType();
        $all = [];
        $check = [];
        foreach ($allMediaType as $item){
            $all[] = $item['type'];
        }
        foreach ($mediaTypes as $item){
            $check[] = $item['type'];
        }
        
        $diff = array_diff($all, $check);
        foreach ($allMediaType as $key => $value) {
            if(in_array($value['type'], $diff)){
                $appendArray['media_type_id'][] = ['type' => $value['type'], 'check' =>  false ];
            }else{
                $appendArray['media_type_id'][] = ['type' => $value['type'], 'check' =>  true ];
            }

        }

        $requestArray = array_merge($requestArray, $appendArray);
        return $requestArray;
    }

    /**
     * @param Part $part
     * @return array
     */
    protected function getFileUploads(Part $part) : array {
        $fileArr = [];
        foreach($part->files as $file){
            $fileArr[] = ['is_scanned' => $file->is_scanned, 'name' => $file->true_file_name];
        }
        return $fileArr;
    }


    /**
     * @param array $requestArray
     * @param Part $part
     * @param Request $request
     * @return array
     */
    protected function completedFormatData(array $requestArray, Part $part, Request $request) : array{
        $requestedAt = $request->created_at->format('Y-m-d g:i A');
        $completedAt = $request->fulfilled_on->format('Y-m-d g:i A');
        $indexArr = [];
        foreach ($part->indexes as $index) {
            $label = '';
            if ($index->indexType != null){
                $label = $index->indexType->index_label;
            }
            $indexArr[] = $label . ' : ' . $index->part_index_value;
        }
        $indexes = implode(', ',$indexArr);
        $appendArray = ['requested_at'=> $requestedAt, 'index'=>$indexes, 'completed_at'=>$completedAt,
            'completed_by'=>$request->fulfilledBy->name, 'download'=>'part'];
        $requestArray = array_merge($requestArray, $appendArray);
        return $requestArray;
    }

    /**
     * @param Collection $partLists
     * @param IndexTypesCollection $indexTypes
     * @return array
     */
    protected function partFormat(Collection $partLists, IndexTypesCollection $indexTypes) : array{
        //TODO: find way to represent part/indexes without loop inside loop
        $parts = $partLists->map(function ($partList) use ($indexTypes) {
            $indexes = $partList->indexes;
            $part = ['selectPart' => false, 'part_name' => $partList->part_name, 'box_name' => $partList->box->box_name,
                'part_id' => $partList->id];
            foreach ($indexTypes as $indexType) {
                $part[$indexType->index_internal_name] = '';
            }
            foreach ($indexes as $index) {
                $part[$index->indexType->index_internal_name] = $index->part_index_value;
            }
            return $part;
        })->toArray();
        return $parts;
    }
}
