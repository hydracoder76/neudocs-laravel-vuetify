<?php
/**
 * User: mlawson
 * Date: 2018-12-15
 * Time: 17:40
 */

namespace NeubusSrm\Repositories;

use NeubusSrm\Lib\Exceptions\NeuEntityNotFoundException;
use NeubusSrm\Models\Indexing\PartIndex;

/**
 * Class PartIndexRepository
 * @package NeubusSrm\Repositories
 */
class PartIndexRepository implements NeuSrmRepository
{

	/**
	 * @return string
	 */
	public function getModelClass(): string {
		return PartIndex::class;
	}

	/**
	 * @param array $partIndexData
	 * @return int
	 */
	public function addNewPartIndex(array $partIndexData) : int {
		$partIndex = PartIndex::create($partIndexData);
		return $partIndex->id;
	}

	/**
	 * @param int $id
	 * @return PartIndex
	 * @throws \Throwable
	 */
	public function getPartIndexById(int $id) : PartIndex {
		$partIndex = PartIndex::find($id);
		$shouldThrow = $partIndex == null || count($partIndex) > 1;
		throw_if($shouldThrow,
			NeuEntityNotFoundException::class, 'No part index found by that id');
		return $partIndex;
	}


}