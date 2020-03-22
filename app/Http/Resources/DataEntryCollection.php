<?php

namespace NeubusSrm\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;


class DataEntryCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        $d = $this->collection->keyBy('box_id')->map(function($col){
            return [
                'box_id' => $col->box_id,
                'box_name' => $col->box_name,
                'part_name' => $col->part_name,
                'box_location_code' => $col->box_location_code,
                'col_index_value' => collect([$col->part_index_value])->implode(' | ')
            ];
        });


        return [
            'data'=> $d
        ];
    }

}
