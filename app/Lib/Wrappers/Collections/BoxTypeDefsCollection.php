<?php
/**
 * User: aho
 * Date: 2020-02-20
 * Time: 08:39
 */

namespace NeubusSrm\Lib\Wrappers\Collections;

use NeubusSrm\Models\Indexing\BoxTypeDef;
use Illuminate\Database\Eloquent\Collection;


/**
 * Class BoxTypeDefsCollection
 * @package NeubusSrm\Lib\Wrappers\Collections
 */
class BoxTypeDefsCollection extends Collection implements NeuTypedCollection
{
	/**
	 * @return string
	 */
	public function getCollectionType(): string {
		return BoxTypeDef::class;
	}


}
