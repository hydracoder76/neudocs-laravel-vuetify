<?php
/**
 * User: mlawson
 * Date: 10/30/18
 * Time: 12:16 PM
 */

namespace NeubusSrm\Models\Indexing;


use Alsofronie\Uuid\UuidModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use NeubusSrm\Lib\Logging\NeuLoggableDetails;
use NeubusSrm\Lib\Traits\LegacySoftDeletes;
use NeubusSrm\Lib\Wrappers\Collections\PartsCollection;
use NeubusSrm\Models\Auth\User;
use NeubusSrm\Models\Org\MediaType;
use NeubusSrm\Models\Org\Project;
use NeubusSrm\Models\Org\Request;


/**
 * Class Part
 *
 * @package NeubusSrm\Models\Indexing
 * @property string $id
 * @property string $part_name
 * @property string|null $part_description
 * @property string|null $project_id
 * @property string $created_by
 * @property string|null $last_updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property string|null $part_location_code
 * @property int $box_id
 * @property-read \NeubusSrm\Models\Indexing\Box $box
 * @property-read \NeubusSrm\Models\Auth\User $createdBy
 * @property-read \NeubusSrm\Lib\Wrappers\Collections\PartIndexesCollection|\NeubusSrm\Models\Indexing\PartIndex[] $indexes
 * @property-read \NeubusSrm\Models\Org\Project|null $project
 * @property-read \Illuminate\Database\Eloquent\Collection|\NeubusSrm\Models\Org\Request[] $requests
 * @property-read \NeubusSrm\Models\Auth\User|null $updatedBy
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\Part newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\Part newQuery()
 * @method static \Illuminate\Database\Query\Builder|\NeubusSrm\Models\Indexing\Part onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\Part query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\Part whereBoxId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\Part whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\Part whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\Part whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\Part whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\Part whereLastUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\Part wherePartDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\Part wherePartLocationCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\Part wherePartName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\Part whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\Part whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\NeubusSrm\Models\Indexing\Part withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\NeubusSrm\Models\Indexing\Part withoutTrashed()
 * @mixin \Eloquent
 * @property-read \NeubusSrm\Lib\Wrappers\Collections\FileUploadCollection|\NeubusSrm\Models\Indexing\FileUpload[] $files
 * @property bool $uploaded_to
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\Part whereUploadedTo($value)
 * @property bool $is_deleted
 * @property-read \NeubusSrm\Lib\Wrappers\Collections\IndexTypesCollection|\NeubusSrm\Models\Indexing\IndexType[] $indexTypes
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\Part whereIsDeleted($value)
 */
class Part extends Model implements NeuLoggableDetails
{

    use LegacySoftDeletes, UuidModelTrait,
        SoftDeletes {
        LegacySoftDeletes::runSoftDelete insteadof SoftDeletes;
        LegacySoftDeletes::restore insteadof SoftDeletes;
        LegacySoftDeletes::trashed insteadof SoftDeletes;
        LegacySoftDeletes::bootSoftDeletes insteadof SoftDeletes;
    }

    const DELETED_AT = 'is_deleted';

    public $incrementing = false;

    // will automatically use the parts table

    protected $fillable = [
        'box_id',
        'part_name',
        'part_location_code',
        'part_description',
        'project_id',
        'created_by',
        'last_updated_by',
        'uploaded_to'
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
    public function project() {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function createdBy() {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function updatedBy() {
        return $this->belongsTo(User::class, 'last_updated_by', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function indexes() {
        return $this->hasMany(PartIndex::class, 'part_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function indexTypes() : HasManyThrough {
        return $this->hasManyThrough(
            IndexType::class,
            PartIndex::class,
            'part_id', // Foreign key on PartIndex table...
            'id', // Foreign key on IndexType table...
            'id', // Local key on Part table...
            'index_type_id' // Local key on PartIndex table...
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function requests(){
        return $this->belongsToMany(Request::class, 'request_parts', 'part_id_ref', 'request_id_ref')->withTimestamps();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function files(){
        return $this->hasMany(FileUpload::class, 'part_id', 'id');
    }

	/**
	 * @param array $models
	 * @return \Illuminate\Database\Eloquent\Collection|PartsCollection
	 */
    public function newCollection(array $models = []){
        return new PartsCollection($models);
    }

    /**
     * @param Collection $arguments
     * @return string
     */
    public function getDetailsForNeuLog(Collection $arguments): string {
        $strArr = [
            'message' => sprintf(' %s', $arguments->get('message')),
            'fields' => [
                'part_name' => sprintf('Part Number : %s', $arguments->get('part_name')),
            ],
            'responsible_table' => $this->getTable(),
            'record_id' => (string) $this->id,
            'company_id' => $arguments->get('company_id')
        ];
        foreach($arguments->get('part_index_value') as $key => $value){
            $strArr['fields'][$key] = sprintf($key . ' : %s', $value);
        }
        return json_encode($strArr);
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function mediaTypes()
    {        
        return $this->belongsToMany(MediaType::class, 'part_media_types', 'part_id', 'media_type_id');
    }
}
