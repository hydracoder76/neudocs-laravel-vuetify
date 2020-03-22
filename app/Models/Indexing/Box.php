<?php
/**
 * User: mlawson
 * Date: 10/30/18
 * Time: 12:08 PM
 */

namespace NeubusSrm\Models\Indexing;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use NeubusSrm\Lib\Logging\NeuLoggableDetails;
use NeubusSrm\Lib\Traits\LegacySoftDeletes;
use NeubusSrm\Models\Auth\User;
use NeubusSrm\Models\Org\Company;
use NeubusSrm\Models\Org\Project;


/**
 * NeubusSrm\Models\Indexing\Box
 *
 * @property int $id
 * @property string $box_name
 * @property string|null $box_location_code
 * @property string $company_id
 * @property string|null $project_id
 * @property bool $has_pending_requests
 * @property string $created_by
 * @property string|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property string $box_status
 * @property-read \NeubusSrm\Models\Org\Company $company
 * @property-read \NeubusSrm\Models\Auth\User $createdBy
 * @property-read \NeubusSrm\Lib\Wrappers\Collections\PartsCollection|\NeubusSrm\Models\Indexing\Part[] $parts
 * @property-read \NeubusSrm\Models\Org\Project|null $project
 * @property-read \NeubusSrm\Models\Auth\User|null $updatedBy
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\Box newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\Box newQuery()
 * @method static \Illuminate\Database\Query\Builder|\NeubusSrm\Models\Indexing\Box onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\Box query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\Box whereBoxLocationCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\Box whereBoxName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\Box whereBoxStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\Box whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\Box whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\Box whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\Box whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\Box whereHasPendingRequests($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\Box whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\Box whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\Box whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\Box whereUpdatedBy($value)
 * @method static \Illuminate\Database\Query\Builder|\NeubusSrm\Models\Indexing\Box withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\NeubusSrm\Models\Indexing\Box withoutTrashed()
 * @mixin \Eloquent
 * @property string $box_type
 * @property string|null $rfid
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\Box whereBoxType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\Box whereRfid($value)
 * @property bool $is_deleted
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\Box whereIsDeleted($value)
 */
class Box extends Model implements NeuLoggableDetails
{

    use LegacySoftDeletes,
        SoftDeletes {
        LegacySoftDeletes::runSoftDelete insteadof SoftDeletes;
        LegacySoftDeletes::restore insteadof SoftDeletes;
        LegacySoftDeletes::trashed insteadof SoftDeletes;
        LegacySoftDeletes::bootSoftDeletes insteadof SoftDeletes;
    }

    const DELETED_AT = 'is_deleted';

    protected $table = 'boxes';

    protected $fillable = [
        'box_name',
        'box_type',
        'box_location_code',
        'company_id',
        'project_id',
        'has_pending_requests',
        'created_by',
        'updated_by',
	    'box_status',
        'rfid'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company() {
        return $this->belongsTo(Company::class, 'company_id', 'id');
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
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
    public function parts(){
        return $this->hasMany(Part::class, 'box_id', 'id');
    }

    /**
     * @param Collection $arguments
     * @return string
     */
    public function getDetailsForNeuLog(Collection $arguments) : string {
        // the first placeholder is just the action that took place for the box
        $strArr = [
            'message' => sprintf(' %s', $arguments->get('message')),
            'fields' => [
                'name' => sprintf('Box Name : %s', $arguments->get('box_name')),
                'location' => sprintf('Location : %s', $arguments->get('box_location_code')),
                'rfid' => sprintf('RFID : %s', $arguments->get('rfid')),
                'box_type' => sprintf('Box Type : %s', $arguments->get('box_type')),
            ],
            'responsible_table' => $this->getTable(),
            'record_id' => (string) $this->id,
            'company_id' => (string) $this->company_id
        ];
        return json_encode($strArr);
    }
}
