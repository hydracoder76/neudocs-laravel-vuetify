<?php
/**
 * User: mlawson
 * Date: 2018-12-04
 * Time: 13:36
 */

namespace NeubusSrm\Lib\Wrappers\Collections;

use Illuminate\Database\Eloquent\Collection;
use NeubusSrm\Models\Org\ProjectMediaType;

/**
 * Class ProjectMediaTypesCollection
 * @package NeubusSrm\Lib\Wrappers\Collections
 */
class ProjectMediaTypesCollection extends Collection implements NeuTypedCollection
{
    /**
     * @return string
     */
    public function getCollectionType(): string {
        ProjectMediaType::class;
    }

}