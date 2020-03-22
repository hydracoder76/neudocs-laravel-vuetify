<?php
/**
 * User: aho
 * Date: 2018-11-30
 * Time: 11:56
 */

namespace NeubusSrm\Lib\Wrappers\Collections;


use Illuminate\Database\Eloquent\Collection;
use NeubusSrm\Models\Org\User;


class UsersCollection extends Collection implements NeuTypedCollection
{

	/**
	 * @return string
	 */
	public function getCollectionType(): string {
		return User::class;
	}
}