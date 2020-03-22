<?php
/**
 * User: mlawson
 * Date: 2018-12-04
 * Time: 13:36
 */

namespace NeubusSrm\Lib\Wrappers\Collections;

use Illuminate\Database\Eloquent\Collection;
use NeubusSrm\Models\Org\Request;

/**
 * Class PartsCollection
 * @package NeubusSrm\Lib\Wrappers\Collections
 */
class RequestsCollection extends Collection implements NeuTypedCollection
{
    /**
     * @return string
     */
    public function getCollectionType(): string {
        Request::class;
    }

}