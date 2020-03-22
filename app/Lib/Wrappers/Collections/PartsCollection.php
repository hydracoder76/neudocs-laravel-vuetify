<?php
/**
 * User: mlawson
 * Date: 2018-12-04
 * Time: 13:36
 */

namespace NeubusSrm\Lib\Wrappers\Collections;

use Illuminate\Database\Eloquent\Collection;
use NeubusSrm\Models\Indexing\Part;

/**
 * Class PartsCollection
 * @package NeubusSrm\Lib\Wrappers\Collections
 */
class PartsCollection extends Collection implements NeuTypedCollection
{
	/**
	 * @return string
	 */
	public function getCollectionType(): string {
		Part::class;
	}

}