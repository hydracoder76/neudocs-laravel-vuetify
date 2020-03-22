<?php
/**
 * User: mlawson
 * Date: 2019-01-27
 * Time: 13:11
 */

namespace NeubusSrm\Lib\Traits;

use Illuminate\Database\Eloquent\Model;

/**
 * Trait WorksWithModels
 * @package NeubusSrm\Models\Traits
 */
trait WorksWithModels
{
    /**
     * @param string|integer $id
     * @return mixed $modelClassName|static
     */
    public function getById($id) : Model {
        $modelClassName = static::getModelClass();
        return $modelClassName::whereId($id)->first();
    }

}