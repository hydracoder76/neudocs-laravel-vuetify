<?php
/**
 * User: mlawson
 * Date: 11/12/18
 * Time: 1:16 PM
 */

namespace NeubusSrm\Models\Auth;

use Illuminate\Database\Eloquent\Model;

/**
 * Class VerificationToken
 *
 * @package NeubusSrm\Models\Auth
 * @property int $id
 * @property string $token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \NeubusSrm\Models\Auth\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Auth\VerificationToken whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Auth\VerificationToken whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Auth\VerificationToken whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Auth\VerificationToken whereUpdatedAt($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Auth\VerificationToken newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Auth\VerificationToken newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Auth\VerificationToken query()
 * @property bool $is_deleted
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Auth\VerificationToken whereIsDeleted($value)
 */
class VerificationToken extends Model
{

	protected $fillable = ['token'];


	public $timestamps = true;

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function user() {
		return $this->belongsTo(User::class, 'verification_token_id', 'id');
	}
}