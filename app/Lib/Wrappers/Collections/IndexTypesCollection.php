<?php
/**
 * User: mlawson
 * Date: 2018-12-15
 * Time: 18:38
 */

namespace NeubusSrm\Lib\Wrappers\Collections;

use Illuminate\Database\Eloquent\Collection;
use NeubusSrm\Models\Indexing\IndexType;

/**
 * Class IndexTypesCollection
 * @package NeubusSrm\Lib\Wrappers\Collections
 */
class IndexTypesCollection extends Collection implements NeuTypedCollection
{
	/**
	 * @return string
	 */
	public function getCollectionType(): string {
		return IndexType::class;
	}


}