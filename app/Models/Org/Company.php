<?php
/**
 * User: mlawson
 * Date: 10/30/18
 * Time: 12:04 PM
 */

namespace NeubusSrm\Models\Org;


use Alsofronie\Uuid\UuidModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use NeubusSrm\Lib\Traits\LegacySoftDeletes;
use NeubusSrm\Lib\Wrappers\Collections\CompaniesCollection;
use NeubusSrm\Models\Auth\User;
use NeubusSrm\Models\Indexing\Box;

/**
 * Class Company
 *
 * @package NeubusSrm\Models\Org
 * @property string $id
 * @property string $company_name
 * @property string $company_access_type
 * @property string|null $company_contact
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\NeubusSrm\Models\Indexing\Box[] $boxes
 * @property-read \Illuminate\Database\Eloquent\Collection|\NeubusSrm\Models\Org\Project[] $projects
 * @property-read \Illuminate\Database\Eloquent\Collection|\NeubusSrm\Models\Org\Project[] $schemas
 * @property-read \Illuminate\Database\Eloquent\Collection|\NeubusSrm\Models\Auth\User[] $users
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Org\Company newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Org\Company newQuery()
 * @method static \Illuminate\Database\Query\Builder|\NeubusSrm\Models\Org\Company onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Org\Company query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Org\Company whereCompanyAccessType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Org\Company whereCompanyContact($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Org\Company whereCompanyName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Org\Company whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Org\Company whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Org\Company whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Org\Company whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\NeubusSrm\Models\Org\Company withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\NeubusSrm\Models\Org\Company withoutTrashed()
 * @mixin \Eloquent
 * @property bool $is_deleted
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Org\Company whereIsDeleted($value)
 */
class Company extends Model
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

    protected $primaryKey = 'id'; // just in case the fact it's a uuid makes eloquent freak out. can probably remove

    protected $table = 'companies';

    protected $fillable = ['company_name', 'company_access_type', 'company_contact', 'deleted_at'];

    protected $hidden = ['company_contact']; // 01/10/2019 hiding on the front end, but may be used in the future
	// so for now just hide it

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function boxes() {
        return $this->hasMany(Box::class, 'company_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function projects() {
        return $this->hasMany(Project::class, 'company_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function schemas() {
        return $this->belongsToMany(Project::class, 'project_schemas', 'company_id', 'project_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users() {
        return $this->hasMany(User::class, 'company_id', 'id');
    }

    /**
     * @param array $models
     * @return \Illuminate\Database\Eloquent\Collection|ProjectsCollection
     */
    public function newCollection(array $models = []) {
        parent::newCollection($models);
        return new CompaniesCollection($models);
    }

}
