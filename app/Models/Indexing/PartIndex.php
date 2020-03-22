<?php
/**
 * User: mlawson
 * Date: 10/30/18
 * Time: 12:15 PM
 */

namespace NeubusSrm\Models\Indexing;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use NeubusSrm\Lib\Traits\LegacySoftDeletes;
use NeubusSrm\Lib\Wrappers\Collections\PartIndexesCollection;


/**
 * Class PartIndex
 *
 * @package NeubusSrm\Models\Indexing
 * @property int $id
 * @property string|null $part_index_value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property string|null $index_location_code
 * @property int $index_type_id
 * @property int $box_id
 * @property string|null $part_id
 * @property-read \NeubusSrm\Models\Indexing\IndexType $indexType
 * @property-read \NeubusSrm\Models\Indexing\Part|null $part
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\PartIndex newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\PartIndex newQuery()
 * @method static \Illuminate\Database\Query\Builder|\NeubusSrm\Models\Indexing\PartIndex onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\PartIndex query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\PartIndex whereBoxId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\PartIndex whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\PartIndex whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\PartIndex whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\PartIndex whereIndexLocationCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\PartIndex whereIndexTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\PartIndex wherePartId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\PartIndex wherePartIndexValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\PartIndex whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\NeubusSrm\Models\Indexing\PartIndex withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\NeubusSrm\Models\Indexing\PartIndex withoutTrashed()
 * @mixin \Eloquent
 * @property bool $is_deleted
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\PartIndex whereIsDeleted($value)
 */
class PartIndex extends Model
{

    use LegacySoftDeletes,
        SoftDeletes {
        LegacySoftDeletes::runSoftDelete insteadof SoftDeletes;
        LegacySoftDeletes::restore insteadof SoftDeletes;
        LegacySoftDeletes::trashed insteadof SoftDeletes;
        LegacySoftDeletes::bootSoftDeletes insteadof SoftDeletes;
    }

    const DELETED_AT = 'is_deleted';
    protected $table = 'part_indexes';

    protected $fillable = [
        'part_id',
        'part_index_value',
	    'index_type_id',
	    'box_id'
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function part() {
        return $this->belongsTo(Part::class, 'part_id', 'id');
    }

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
    public function indexType(){
        return $this->belongsTo(IndexType::class, 'index_type_id', 'id');
    }

	/**
	 * @param array $models
	 * @return \Illuminate\Database\Eloquent\Collection|PartIndexesCollection
	 */
    public function newCollection(array $models = []) {
	    parent::newCollection($models);
	    return new PartIndexesCollection($models);
    }

}