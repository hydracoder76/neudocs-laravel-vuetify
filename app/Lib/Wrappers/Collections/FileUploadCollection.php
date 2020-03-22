<?php
/**
 * User: mlawson
 * Date: 2019-01-27
 * Time: 13:51
 */

namespace NeubusSrm\Lib\Wrappers\Collections;

use Illuminate\Database\Eloquent\Collection;
use NeubusSrm\Models\Indexing\FileUpload;

/**
 * Class FileUploadCollection
 * @package NeubusSrm\Lib\Wrappers\Collections
 */
class FileUploadCollection extends Collection implements  NeuTypedCollection
{
    /**
     * @return string
     */
    public function getCollectionType(): string {
        return FileUpload::class;
    }


}