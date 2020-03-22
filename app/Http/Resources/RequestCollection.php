<?php

namespace NeubusSrm\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;


class RequestCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'results'=>$this->collection->map(function($col){
                return [
                    'id' => $col->id,
                    'company_id' => $col->company_id,
                    'created_by' => $col->created_by,
                    'is_fulfilled' => $col->is_fulfilled,
                    'is_in_process' => $col->is_in_process,
                    'created_at' => $col->created_at,
                    'project_id' => $col->project_id,
                ];
            }),
            'current_page'=> $this->currentPage(),
            'total'=>$this->total()
        ];
    }
}
