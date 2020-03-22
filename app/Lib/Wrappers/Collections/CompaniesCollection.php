<?php
/**
 * User: mlawson
 * Date: 2018-12-31
 * Time: 08:39
 */

namespace NeubusSrm\Lib\Wrappers\Collections;

use NeubusSrm\Models\Org\Company;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class CompaniesCollection
 * @package NeubusSrm\Lib\Wrappers\Collections
 */
class CompaniesCollection extends Collection implements NeuTypedCollection
{
	/**
	 * @return string
	 */
	public function getCollectionType(): string {
		return Company::class;
	}


}