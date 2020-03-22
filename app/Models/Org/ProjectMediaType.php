<?php

namespace NeubusSrm\Models\Org;

use Illuminate\Database\Eloquent\Model;

class ProjectMediaType extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'project_media_types';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'project_id',
        'media_type_id',
        'created_on',
    ];    
}
