<?php
/**
 * Created by PhpStorm.
 * User: mlawson
 * Date: 2019-03-13
 * Time: 11:00
 */

namespace NeubusSrm\Repositories;

use Illuminate\Support\Facades\Auth;
use NeubusSrm\Lib\Exceptions\NeuEntityNotFoundException;
use NeubusSrm\Models\Indexing\Box;
use NeubusSrm\Models\Org\BoxLocationHistory;
use NeubusSrm\Lib\Exceptions\NeuDataStoreException;
/**
 * Class BoxLocationHistoryRepository
 * @package NeubusSrm\Repositories
 */
class BoxLocationHistoryRepository implements NeuSrmRepository
{
    /**
     * @return string
     */
    public function getModelClass(): string {
        return BoxLocationHistory::class;
    }

    /**
     * @param array $attributes
     * @return BoxLocationHistory
     * @throws NeuDataStoreException
     */
    public function addNewBoxLocationHistoryEntity(array $attributes) : BoxLocationHistory {

        try {
            return BoxLocationHistory::create($attributes);
        }
        catch (\Exception $exception) {
            throw new NeuDataStoreException('Unable to create new box location, 
	    	please try again or contact an administrator');
        }

    }

    /**
     * @param string $boxId
     * @throws \NeubusSrm\Lib\Exceptions\NeuSrmException
     */
    public function updatedBy(string $boxId) : void {
        $box = Box::where('id', $boxId)->first();
        neu_throw_if($box == null, NeuEntityNotFoundException::class, 'No boxes with this id');
        $userId = Auth::user()->id;
        $box->updated_by = $userId;
        $box->save();
    }
}
