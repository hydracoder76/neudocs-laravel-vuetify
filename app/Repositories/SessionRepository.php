<?php
/**
 * Created by PhpStorm.
 * User: mlawson
 * Date: 2019-03-09
 * Time: 14:07
 */

namespace NeubusSrm\Repositories;

use NeubusSrm\Models\Util\Session;

/**
 * Class SessionRepository
 * @package NeubusSrm\Repositories
 */
class SessionRepository implements NeuSrmRepository
{
    /**
     * @return string
     */
    public function getModelClass(): string {
        return Session::class;
    }

}
