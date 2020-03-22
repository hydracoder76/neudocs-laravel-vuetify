<?php
/**
 * User: mlawson
 * Date: 2018-12-15
 * Time: 16:34
 */

namespace NeubusSrm\Models\Relational;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use NeubusSrm\Lib\Traits\LegacySoftDeletes;

/**
 * Class IndexTypeRel
 *
 * @package NeubusSrm\Models\Relational
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Relational\IndexTypeRel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Relational\IndexTypeRel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Relational\IndexTypeRel query()
 * @mixin \Eloquent
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\NeubusSrm\Models\Relational\IndexTypeRel onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Query\Builder|\NeubusSrm\Models\Relational\IndexTypeRel withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\NeubusSrm\Models\Relational\IndexTypeRel withoutTrashed()
 */
class IndexTypeRel extends Model
{

    use LegacySoftDeletes,
        SoftDeletes {
        LegacySoftDeletes::runSoftDelete insteadof SoftDeletes;
        LegacySoftDeletes::restore insteadof SoftDeletes;
        LegacySoftDeletes::trashed insteadof SoftDeletes;
        LegacySoftDeletes::bootSoftDeletes insteadof SoftDeletes;
    }

    const DELETED_AT = 'is_deleted';

	protected $table = 'index_type_rel';

	protected $fillable = ['index_type_id', 'index_id'];

	protected $primaryKey = null;

	public $timestamps = false;
}