<?php

namespace NeubusSrm\Lib\Wrappers\Collections;

use Illuminate\Database\Eloquent\Collection;
use NeubusSrm\Models\Org\MediaType;

/**
 * Class MediaTypesCollection
 * @package NeubusSrm\Lib\Wrappers\Collections
 */
class MediaTypesCollection extends Collection implements NeuTypedCollection
{
	/**
	 * @return string
	 */
	public function getCollectionType(): string {
		return MediaType::class;
	}



}
