<?php
/**
 * User: mlawson
 * Date: 10/30/18
 * Time: 12:27 PM
 */

namespace NeubusSrm\Models\Org;


use Alsofronie\Uuid\UuidModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use NeubusSrm\Lib\Logging\NeuLoggableDetails;
use NeubusSrm\Lib\Traits\LegacySoftDeletes;
use NeubusSrm\Lib\Wrappers\Collections\RequestsCollection;
use NeubusSrm\Models\Auth\User;
use NeubusSrm\Models\Indexing\Part;


/**
 * Class Request
 *
 * @package NeubusSrm\Models\Org
 * @property string $id
 * @property string $company_id
 * @property string $created_by
 * @property string $updated_by
 * @property bool $is_fulfilled
 * @property bool $is_in_process
 * @property string|null $fulfilled_by
 * @property string|null $fulfilled_on
 * @property bool $is_reviewed
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property string|null $project_id
 * @property-read \NeubusSrm\Models\Org\Company $company
 * @property-read \NeubusSrm\Models\Auth\User $createdBy
 * @property-read \NeubusSrm\Models\Auth\User $fulfilledBy
 * @property-read \NeubusSrm\Lib\Wrappers\Collections\PartsCollection|\NeubusSrm\Models\Indexing\Part[] $parts
 * @property-read \NeubusSrm\Models\Org\Project|null $project
 * @property-read \NeubusSrm\Models\Auth\User $updatedBy
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Org\Request newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Org\Request newQuery()
 * @method static \Illuminate\Database\Query\Builder|\NeubusSrm\Models\Org\Request onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Org\Request query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Org\Request whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Org\Request whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Org\Request whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Org\Request whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Org\Request whereFulfilledBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Org\Request whereFulfilledOn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Org\Request whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Org\Request whereIsFulfilled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Org\Request whereIsInProcess($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Org\Request whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Org\Request whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Org\Request whereUpdatedBy($value)
 * @method static \Illuminate\Database\Query\Builder|\NeubusSrm\Models\Org\Request withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\NeubusSrm\Models\Org\Request withoutTrashed()
 * @mixin \Eloquent
 * @property string|null $request_name
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Org\Request whereRequestName($value)
 * @property string $comment
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Org\Request whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Org\Request whereIsReviewed($value)
 * @property bool $is_deleted
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Org\Request done()
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Org\Request whereIsDeleted($value)
 * @property string|null $external_comment
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Org\Request whereExternalComment($value)
 */
class Request extends Model implements NeuLoggableDetails
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

    // should automatically use the requests table

    protected $fillable = [
        'company_id',
        'created_by',
        'updated_by',
        'is_fulfilled',
        'is_in_process',
        'fulfilled_by',
        'fulfilled_on',
        'index_string',
        'is_reviewed',
        'comment',
        'external_comment'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'fulfilled_on'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company() {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function createdBy() {
        return $this->hasOne(User::class, 'created_by', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function updatedBy() {
        return $this->hasOne(User::class, 'updated_by', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function fulfilledBy() {
        return $this->belongsTo(User::class, 'fulfilled_by', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function project() {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function parts() {
        return $this->belongsToMany(Part::class, 'request_parts', 'request_id_ref', 'part_id_ref')->withTimestamps();
    }

    /**
     * @param array $models
     * @return RequestsCollection
     */
    public function newCollection(array $models = []) {
        return new RequestsCollection($models);
    }

    /**
     * @param Collection $arguments
     * @return string
     */
    public function getDetailsForNeuLog(Collection $arguments): string {
        $strArr = [
            'message' => sprintf('%s', $arguments->get('message')),
            'fields' => [
                'request_name' => sprintf('Request Number: %s', $arguments->get('request_name')),
            ],
            'responsible_table' => $this->getTable(),
            'record_id' => (string)$this->id,
            'company_id' => (string)$this->company_id
        ];
        return json_encode($strArr);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDone($query) {
        return $query->where('is_fulfilled', true)->orWhere('is_reviewed', true);
    }
}