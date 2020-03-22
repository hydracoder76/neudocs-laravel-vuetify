<?php

namespace NeubusSrm\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ProjectCollection extends ResourceCollection
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
            'data'=>$this->collection->map(function($col){
                return [
                    'id' => $col->id,
                    'project_name'=>$col->project_name,
                    'project_description'=>$col->project_description,
                    'company_id' => $col->company_id,
                    'project_owner_id' => $col->project_owner_id,
                    'company_name' => $col->company['company_name'],
                    'project_owner_name' => $col->owner['name'],

                ];
            }),
            'current_page'=> $this->currentPage(),
            'total'=>$this->total()
        ];
    }
}
