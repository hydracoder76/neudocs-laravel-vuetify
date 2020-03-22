<?php

namespace NeubusSrm\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;


class UserCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if(method_exists($this,'currentPage'))
            $currentPage = $this->currrentPage();
        else
            $currentPage = 1;

        if(method_exists($this,'total'))
            $total = $this->total();
        else
            $total = 25;

        return [
           'data'=>$this->collection->map(function($col){
                return [
                    'id' => $col->id,
                    'name' => $col->name,
                    'email' => $col->email,
                    'company_id' => $col->company_id,
                    'company_name' => $col->company['company_name'],
                    'role' => $col->role,
                ];
            }),
          'current_page'=> $currentPage,
          'total'=>$total
        ];
    }
}
