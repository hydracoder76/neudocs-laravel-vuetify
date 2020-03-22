<?php
/**
 * User: mlawson
 * Date: 10/30/18
 * Time: 12:11 PM
 */

namespace NeubusSrm\Models\Indexing;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use NeubusSrm\Lib\Traits\LegacySoftDeletes;


/**
 * NeubusSrm\Models\Indexing\FileType
 *
 * @property int $id
 * @property string|null $file_type_name
 * @property string $file_type_ext
 * @property string|null $file_type_full_mime
 * @property int $project_schema_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\FileType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\FileType newQuery()
 * @method static \Illuminate\Database\Query\Builder|\NeubusSrm\Models\Indexing\FileType onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\FileType query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\FileType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\FileType whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\FileType whereFileTypeExt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\FileType whereFileTypeFullMime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\FileType whereFileTypeName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\FileType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\FileType whereProjectSchemaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\FileType whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\NeubusSrm\Models\Indexing\FileType withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\NeubusSrm\Models\Indexing\FileType withoutTrashed()
 * @mixin \Eloquent
 * @property bool $is_deleted
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\FileType whereIsDeleted($value)
 */
class FileType extends Model
{

    use LegacySoftDeletes,
        SoftDeletes {
        LegacySoftDeletes::runSoftDelete insteadof SoftDeletes;
        LegacySoftDeletes::restore insteadof SoftDeletes;
        LegacySoftDeletes::trashed insteadof SoftDeletes;
        LegacySoftDeletes::bootSoftDeletes insteadof SoftDeletes;
    }

    const DELETED_AT = 'is_deleted';

    protected $fillable = [
        'file_type_name',
        'file_type_ext',
        'file_type_full_mime'
    ];

}