<?php

namespace NeubusSrm\Models\Relational;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use NeubusSrm\Lib\Traits\LegacySoftDeletes;
use NeubusSrm\Models\Indexing\Part;
use NeubusSrm\Models\Org\Request;
use NeubusSrm\Models\Auth\User;
use NeubusSrm\Lib\Wrappers\Collections\RequestPartsCollection;

/**
 * Class RequestPart
 *
 * @package NeubusSrm\Models\Relational
 * @property string $part_id_ref
 * @property string $request_id_ref
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Relational\RequestPart newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Relational\RequestPart newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Relational\RequestPart query()
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Relational\RequestPart wherePartIdRef($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Relational\RequestPart whereRequestIdRef($value)
 * @mixin \Eloquent
 * @property string|null $searchtext
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Relational\RequestPart whereSearchtext($value)
 * @property-read \NeubusSrm\Models\Indexing\Part $part
 * @property-read \NeubusSrm\Models\Org\Request $request
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Relational\RequestPart whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Relational\RequestPart whereUpdatedAt($value)
 * @property string|null $locked_by
 * @property-read \NeubusSrm\Models\Auth\User|null $lockedBy
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Relational\RequestPart whereLockedBy($value)
 * @property bool $is_deleted
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\NeubusSrm\Models\Relational\RequestPart onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Relational\RequestPart whereIsDeleted($value)
 * @method static \Illuminate\Database\Query\Builder|\NeubusSrm\Models\Relational\RequestPart withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\NeubusSrm\Models\Relational\RequestPart withoutTrashed()
 */
class RequestPart extends Model
{

    use LegacySoftDeletes,
        SoftDeletes {
        LegacySoftDeletes::runSoftDelete insteadof SoftDeletes;
        LegacySoftDeletes::restore insteadof SoftDeletes;
        LegacySoftDeletes::trashed insteadof SoftDeletes;
        LegacySoftDeletes::bootSoftDeletes insteadof SoftDeletes;
    }

    const DELETED_AT = 'is_deleted';

    public $incrementing = false;

    protected $primaryKey = null;

    protected $fillable = ['part_id_ref', 'request_ref'];

    protected $table = 'request_parts';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function part(){
        return $this->belongsTo(Part::class, 'part_id_ref', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function request(){
        return $this->belongsTo(Request::class, 'request_id_ref', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lockedBy(){
        return $this->belongsTo(User::class, 'locked_by', 'id');
    }

    public function newCollection(array $models = []){
        return new RequestPartsCollection($models);
    }
}
