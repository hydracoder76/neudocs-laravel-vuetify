<?php
/**
 * Created by PhpStorm.
 * User: mlawson
 * Date: 2019-03-21
 * Time: 19:25
 */

namespace NeubusSrm\Lib\Wrappers\Collections;

use Illuminate\Database\Eloquent\Collection;
use NeubusSrm\Models\Util\SrmLog;

/**
 * Class SrmLogCollection
 * @package NeubusSrm\Lib\Wrappers\Collections
 */
class SrmLogCollection extends Collection implements NeuTypedCollection
{
    /**
     * @return string
     */
    public function getCollectionType() : string {
        return SrmLog::class;
    }
}
