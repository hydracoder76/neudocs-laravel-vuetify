<?php
/**
 * User: mlawson
 * Date: 2018-12-04
 * Time: 07:45
 */

namespace NeubusSrm\Models\Indexing;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use NeubusSrm\Lib\Logging\NeuLoggableDetails;
use NeubusSrm\Lib\Traits\LegacySoftDeletes;
use NeubusSrm\Lib\Wrappers\Collections\IndexTypesCollection;
use NeubusSrm\Models\Auth\User;
use NeubusSrm\Models\Org\Project;

/**
 * NeubusSrm\Models\Indexing\IndexType
 *
 * @property int $id
 * @property string $index_internal_name
 * @property string $index_label
 * @property bool $has_validation
 * @property string|null $validation_regex
 * @property string|null $validation_rule_class_name fq class name to validate the field
 * @property string|null $created_by
 * @property-read \NeubusSrm\Models\Auth\User|null $createdBy
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\IndexType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\IndexType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\IndexType query()
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\IndexType whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\IndexType whereHasValidation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\IndexType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\IndexType whereIndexName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\IndexType whereValidationRegex($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\IndexType whereValidationRuleClassName($value)
 * @mixin \Eloquent
 * @property string|null $index_description
 * @property bool $is_required
 * @property bool $is_required_double
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property string $index_type_name
 * @property string|null $project_id
 * @property-read \NeubusSrm\Lib\Wrappers\Collections\PartIndexesCollection|\NeubusSrm\Models\Indexing\PartIndex[] $partIndexes
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\IndexType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\IndexType whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\IndexType whereIndexDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\IndexType whereIndexInternalName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\IndexType whereIndexLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\IndexType whereIndexTypeName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\IndexType whereIsRequired($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\IndexType whereIsRequiredDouble($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\IndexType whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\IndexType whereUpdatedAt($value)
 * @property-read \NeubusSrm\Models\Org\Project|null $project
 * @property bool $is_deleted
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\NeubusSrm\Models\Indexing\IndexType onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\IndexType whereIsDeleted($value)
 * @method static \Illuminate\Database\Query\Builder|\NeubusSrm\Models\Indexing\IndexType withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\NeubusSrm\Models\Indexing\IndexType withoutTrashed()
 */
class IndexType extends Model implements NeuLoggableDetails
{


    use LegacySoftDeletes,
        SoftDeletes {
        LegacySoftDeletes::runSoftDelete insteadof SoftDeletes;
        LegacySoftDeletes::restore insteadof SoftDeletes;
        LegacySoftDeletes::trashed insteadof SoftDeletes;
        LegacySoftDeletes::bootSoftDeletes insteadof SoftDeletes;
    }

    const DELETED_AT = 'is_deleted';

	const INDEX_TYPES = [
		'text',
		'text_area',
		'date',
		'date_range',
		'multi_select',
		'single_select',
		'email',
		'numeric'
	];

	protected $fillable = [
		'index_type_name',
		'index_internal_name',
		'index_description',
		'is_required',
		'is_required_double',
        'index_label',
		'has_validation',
		'validation_regex',
		'validation_rule_class_name',
        'project_id',
		'created_by'];

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function createdBy() {
		return $this->belongsTo(User::class, 'created_by');
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function partIndexes(){
	    return $this->hasMany(PartIndex::class, 'index_type_id', 'id');
    }

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
    public function project(){
	    return $this->belongsTo(Project::class, 'project_id', 'id');
    }

	/**
	 * @param array $models
	 * @return \Illuminate\Database\Eloquent\Collection|IndexTypesCollection
	 */
	public function newCollection(array $models = []) {
		parent::newCollection($models);
		return new IndexTypesCollection($models);
	}

    /**
     * @param Collection $arguments
     * @return string
     */
	public function getDetailsForNeuLog(Collection $arguments): string {
        $strArr = [
            'message' => sprintf('%s', $arguments->get('message')),
            'fields' => [
                'index_type_name' => sprintf('Type : %s', $arguments->get('index_type_name')),
                'index_type_description' => sprintf('Description : %s', $arguments->get('index_type_description')),
                'index_label' => sprintf('Index Name : %s', $arguments->get('index_label')),
                'index_internal_name' => sprintf('Internal Name : %s', $arguments->get('index_internal_name')),
            ],
            'responsible_table' => $this->getTable(),
            'record_id' => (string) $this->id,
            'company_id' => $arguments->get('company_id')
        ];
        return json_encode($strArr);
    }
}