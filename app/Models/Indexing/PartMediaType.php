<?php

namespace NeubusSrm\Models\Indexing;

use Illuminate\Database\Eloquent\Model;

class PartMediaType extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'part_media_types';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'part_id',
        'media_type_id',
    ];
}
