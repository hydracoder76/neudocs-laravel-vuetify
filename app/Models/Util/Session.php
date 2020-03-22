<?php
/**
 * Created by PhpStorm.
 * User: mlawson
 * Date: 2019-03-09
 * Time: 14:04
 */

namespace NeubusSrm\Models\Util;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use NeubusSrm\Models\Auth\User;

/**
 * This model interacts with Laravel internal tables. Use wisely
 * Class Session
 *
 * @package NeubusSrm\Models\Util
 * @property int $id
 * @property string|null $user_id
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property string $payload
 * @property int $last_activity
 * @property-read \NeubusSrm\Models\Auth\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Util\Session newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Util\Session newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Util\Session query()
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Util\Session whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Util\Session whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Util\Session whereLastActivity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Util\Session wherePayload($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Util\Session whereUserAgent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Util\Session whereUserId($value)
 * @mixin \Eloquent
 */
class Session extends Model
{

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user() : HasOne {
        return $this->hasOne(User::class);
    }
}
