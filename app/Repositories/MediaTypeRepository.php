<?php


namespace NeubusSrm\Repositories;


use Illuminate\Database\Eloquent\Collection;
use NeubusSrm\Lib\Exceptions\NeuSrmException;
use NeubusSrm\Lib\Wrappers\Collections\MediaTypesCollection;
use NeubusSrm\Models\Org\MediaType;


/**
 * Class MediaTypeRepository
 * @package NeubusSrm\Repositories
 */
class MediaTypeRepository implements NeuSrmRepository
{

    /**
     * @return string
     */
    public function getModelClass(): string {
        return MediaType::class;
    }


    /**
     * @return mixed
     */
    public function getAllMediaTypes() : MediaTypesCollection
    {        
        return  MediaType::get();     
    }
}
