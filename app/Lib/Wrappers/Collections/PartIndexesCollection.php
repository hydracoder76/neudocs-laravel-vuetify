<?php
/**
 * User: mlawson
 * Date: 2018-12-15
 * Time: 18:39
 */

namespace NeubusSrm\Lib\Wrappers\Collections;

use Illuminate\Database\Eloquent\Collection;
use NeubusSrm\Models\Indexing\PartIndex;

/**
 * Class PartIndexesCollection
 * @package NeubusSrm\Lib\Wrappers\Collections
 */
class PartIndexesCollection extends Collection implements NeuTypedCollection
{

	/**
	 * @return string
	 */
	public function getCollectionType(): string {
		PartIndex::class;
	}


}