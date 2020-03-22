<?php
/**
 * User: mlawson
 * Date: 11/12/18
 * Time: 12:31 PM
 */

namespace NeubusSrm\Repositories;


/**
 * Interface NeuSrmRepository
 * @package NeubusSrm\Repositories
 */
interface NeuSrmRepository
{

	/**
	 * @return string
	 */
	public function getModelClass() : string;
}