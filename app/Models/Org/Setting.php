<?php

namespace NeubusSrm\Models\Org;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use NeubusSrm\Lib\Traits\LegacySoftDeletes;
use NeubusSrm\Lib\Wrappers\Collections\SettingsCollection;


/**
 * NeubusSrm\Models\Org\Setting
 *
 * @property string $project_id
 * @property string $setting_key
 * @property string $label
 * @property string $value
 * @property-read \NeubusSrm\Models\Org\Project $project
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Org\Setting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Org\Setting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Org\Setting query()
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Org\Setting whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Org\Setting whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Org\Setting whereSettingKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Org\Setting whereValue($value)
 * @mixin \Eloquent
 * @property bool $is_deleted
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\NeubusSrm\Models\Org\Setting onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Org\Setting whereIsDeleted($value)
 * @method static \Illuminate\Database\Query\Builder|\NeubusSrm\Models\Org\Setting withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\NeubusSrm\Models\Org\Setting withoutTrashed()
 */
class Setting extends Model
{

    use LegacySoftDeletes,
        SoftDeletes {
        LegacySoftDeletes::runSoftDelete insteadof SoftDeletes;
        LegacySoftDeletes::restore insteadof SoftDeletes;
        LegacySoftDeletes::trashed insteadof SoftDeletes;
        LegacySoftDeletes::bootSoftDeletes insteadof SoftDeletes;
    }

    const DELETED_AT = 'is_deleted';

    CONST YELLOW_DEFAULT = 10;

    CONST RED_DEFAULT = 14;

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'project_id',
        'setting_key',
        'label',
        'value'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function project(){
        return $this->belongsTo(Project::class);
    }

    /**
     * @param array $models
     * @return SettingsCollection
     */
    public function newCollection(array $models = []) {
        parent::newCollection($models);
        return new SettingsCollection($models);
    }
}