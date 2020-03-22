<?php

namespace NeubusSrm\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'project_name'=>$this->project_name,
            'project_description'=>$this->project_description,
            'company_id' => $this->company_id,
            'project_owner_id' => $this->project_owner_id,
            'company_name' => $this->company['company_name'],
            'project_owner_name' => $this->owner['name'],

        ];
    }
}
