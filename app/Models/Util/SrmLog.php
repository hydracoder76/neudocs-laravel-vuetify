<?php
/**
 * Created by PhpStorm.
 * User: mlawson
 * Date: 2019-03-20
 * Time: 13:43
 */

namespace NeubusSrm\Models\Util;

use Alsofronie\Uuid\UuidModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use NeubusSrm\Lib\Wrappers\Collections\SrmLogCollection;
use NeubusSrm\Models\Auth\User;
use NeubusSrm\Models\Org\Company;
use NeubusSrm\Lib\Traits\LegacySoftDeletes;


/**
 * NeubusSrm\Models\Util\SrmLog
 *
 * @property string $id
 * @property string $user_id
 * @property string $company_id
 * @property string|null $record_id
 * @property string|null $operation
 * @property string $level
 * @property string $message
 * @property string $details
 * @property string|null $ip_address
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property string $responsible_table
 * @property-read \NeubusSrm\Models\Auth\User $user
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Util\SrmLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Util\SrmLog newQuery()
 * @method static \Illuminate\Database\Query\Builder|\NeubusSrm\Models\Util\SrmLog onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Util\SrmLog query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Util\SrmLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Util\SrmLog whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Util\SrmLog whereDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Util\SrmLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Util\SrmLog whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Util\SrmLog whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Util\SrmLog whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Util\SrmLog whereOperation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Util\SrmLog whereRecordId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Util\SrmLog whereResponsibleTable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Util\SrmLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Util\SrmLog whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\NeubusSrm\Models\Util\SrmLog withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\NeubusSrm\Models\Util\SrmLog withoutTrashed()
 * @mixin \Eloquent
 * @property bool $is_deleted
 * @property-read \NeubusSrm\Models\Org\Company|null $company
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Util\SrmLog whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Util\SrmLog whereIsDeleted($value)
 */
class SrmLog extends Model
{

    use LegacySoftDeletes, UuidModelTrait,
        SoftDeletes {
        LegacySoftDeletes::runSoftDelete insteadof SoftDeletes;
        LegacySoftDeletes::restore insteadof SoftDeletes;
        LegacySoftDeletes::trashed insteadof SoftDeletes;
        LegacySoftDeletes::bootSoftDeletes insteadof SoftDeletes;
    }

    const DELETED_AT = 'is_deleted';

    protected $fillable = [
        'id',
        'user_id',
        'record_id',
        'company_id',
        'operation',
        'level',
        'message',
        'details',
        'responsible_table',
        'ip_address'
    ];

    protected $hidden = [
        'deleted_at',
        'updated_at',
        'is_deleted'
    ];

    public $incrementing = false;

    /**
     * @return BelongsTo
     */
    public function user() : BelongsTo {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function company() : BelongsTo {
        return $this->belongsTo(Company::class);
    }

    /**
     * @param array $models
     * @return \Illuminate\Database\Eloquent\Collection|SrmLogCollection
     */
    public function newCollection(array $models = []) : SrmLogCollection {
        parent::newCollection($models);
        return new SrmLogCollection($models);
    }
}
