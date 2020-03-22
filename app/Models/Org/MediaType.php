<?php

namespace NeubusSrm\Models\Org;

use Illuminate\Database\Eloquent\Model;
use NeubusSrm\Lib\Wrappers\Collections\MediaTypesCollection;

class MediaType extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'media_types';

    protected $hidden = ['pivot'];


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type',
    ];    

    public function newCollection(array $models = []) : MediaTypesCollection
    {
		parent::newCollection($models);
		return new MediaTypesCollection($models);
	}
}
