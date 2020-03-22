<?php
/**
 * User: mlawson
 * Date: 10/30/18
 * Time: 12:22 PM
 */

namespace NeubusSrm\Models\Org;


use Alsofronie\Uuid\UuidModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use NeubusSrm\Lib\Traits\LegacySoftDeletes;
use NeubusSrm\Models\Auth\User;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use NeubusSrm\Models\Indexing\Box;
use NeubusSrm\Models\Indexing\IndexType;
use NeubusSrm\Lib\Wrappers\Collections\ProjectsCollection;
use NeubusSrm\Models\Indexing\Part;

/**
 * Class Project
 *
 * @package NeubusSrm\Models\Org
 * @property string $id
 * @property string $project_name
 * @property string|null $project_description
 * @property string|null $project_owner_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property string $company_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\NeubusSrm\Models\Indexing\Box[] $boxes
 * @property-read \NeubusSrm\Models\Org\Company $company
 * @property-read \NeubusSrm\Lib\Wrappers\Collections\IndexTypesCollection|\NeubusSrm\Models\Indexing\IndexType[] $indexes
 * @property-read \NeubusSrm\Models\Auth\User|null $owner
 * @property-read \Illuminate\Database\Eloquent\Collection|\NeubusSrm\Models\Org\Request[] $requests
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Org\Project newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Org\Project newQuery()
 * @method static \Illuminate\Database\Query\Builder|\NeubusSrm\Models\Org\Project onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Org\Project query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Org\Project whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Org\Project whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Org\Project whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Org\Project whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Org\Project whereProjectDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Org\Project whereProjectName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Org\Project whereProjectOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Org\Project whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\NeubusSrm\Models\Org\Project withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\NeubusSrm\Models\Org\Project withoutTrashed()
 * @property-read \NeubusSrm\Lib\Wrappers\Collections\SettingsCollection|\NeubusSrm\Models\Org\Setting[] $settings
 * @mixin \Eloquent
 * @property-read \NeubusSrm\Lib\Wrappers\Collections\PartsCollection|\NeubusSrm\Models\Indexing\Part[] $parts
 * @property bool $is_deleted
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Org\Project whereIsDeleted($value)
 */
class Project extends Model
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

    // will automatically use he projects table

    protected $fillable = [
        'project_name',
        'project_description',
        'project_owner_id',
        'company_id'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company() {
        // second and third param probably not required but it's here for readability
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner() {
        return $this->belongsTo(User::class, 'project_owner_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function boxes() {
        return $this->hasMany(Box::class, 'project_id', 'id');
    }

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
    public function requests(){
        return $this->hasMany(Request::class, 'project_id', 'id');
    }

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
    public function indexes(){
        return $this->hasMany(IndexType::class, 'project_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function settings(){
        return $this->hasMany(Setting::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function parts(){
        return $this->hasMany(Part::class);
    }

    public function newCollection(array $models = []) {
        parent::newCollection($models);
        return new ProjectsCollection($models);
    }

    /**
    * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
    */
    public function mediaTypes() : BelongsToMany
    {
        return $this->belongsToMany(MediaType::class, 'project_media_types', 'project_id', 'media_type_id');         
    }    
}
