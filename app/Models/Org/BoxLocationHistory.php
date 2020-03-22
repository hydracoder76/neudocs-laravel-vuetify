<?php
/**
 * User: aho
 * Date: 2/28/19
 * Time: 12:08 PM
 */

namespace NeubusSrm\Models\Org;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use NeubusSrm\Lib\Traits\LegacySoftDeletes;
use NeubusSrm\Models\Auth\User;
use NeubusSrm\Models\Indexing\Box;


/**
 * Class BoxLocationHistory
 *
 * @package NeubusSrm\Models\Org
 * @property int $id
 * @property string $user_id
 * @property int $box_id
 * @property string $activity
 * @property string $location
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \NeubusSrm\Models\Indexing\Box $box
 * @property-read \NeubusSrm\Models\Auth\User $user
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Org\BoxLocationHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Org\BoxLocationHistory newQuery()
 * @method static \Illuminate\Database\Query\Builder|\NeubusSrm\Models\Org\BoxLocationHistory onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Org\BoxLocationHistory query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Org\BoxLocationHistory whereActivity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Org\BoxLocationHistory whereBoxId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Org\BoxLocationHistory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Org\BoxLocationHistory whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Org\BoxLocationHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Org\BoxLocationHistory whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Org\BoxLocationHistory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Org\BoxLocationHistory whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\NeubusSrm\Models\Org\BoxLocationHistory withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\NeubusSrm\Models\Org\BoxLocationHistory withoutTrashed()
 * @mixin \Eloquent
 * @property bool $is_deleted
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Org\BoxLocationHistory whereIsDeleted($value)
 */
class BoxLocationHistory extends Model
{

    use LegacySoftDeletes,
        SoftDeletes {
        LegacySoftDeletes::runSoftDelete insteadof SoftDeletes;
        LegacySoftDeletes::restore insteadof SoftDeletes;
        LegacySoftDeletes::trashed insteadof SoftDeletes;
        LegacySoftDeletes::bootSoftDeletes insteadof SoftDeletes;
    }

    const DELETED_AT = 'is_deleted';

    const CHECKOUT_ACTIVITY = 'NCHECKOUT';
    const CHECKIN_ACTIVITY = 'NCHECKIN';

    protected $table = 'box_location_history';

    protected $fillable = [
        'box_id',
        'user_id',
        'activity',
        'location'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function box() {
        return $this->belongsTo(Box::class, 'box_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
