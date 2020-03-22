<?php
/**
 * User: mlawson
 * Date: 2019-01-27
 * Time: 12:31
 */

namespace NeubusSrm\Models\Indexing;

use Alsofronie\Uuid\UuidModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;
use NeubusSrm\Lib\Logging\NeuLoggableDetails;
use NeubusSrm\Lib\Traits\LegacySoftDeletes;
use NeubusSrm\Lib\Wrappers\Collections\FileUploadCollection;
use NeubusSrm\Models\Auth\User;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class FileUpload
 *
 * @package NeubusSrm\Models\Indexing
 * @property int $id
 * @property int|null $box_id
 * @property string|null $part_id
 * @property string $uploaded_by
 * @property string $true_file_name
 * @property string $hashed_file_name
 * @property string|null $file_mime
 * @property string $current_fs_location
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \NeubusSrm\Models\Indexing\Box|null $box
 * @property-read \NeubusSrm\Models\Indexing\Part|null $part
 * @property-read \NeubusSrm\Models\Auth\User $uploadedBy
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\FileUpload newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\FileUpload newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\FileUpload query()
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\FileUpload whereBoxId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\FileUpload whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\FileUpload whereCurrentFsLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\FileUpload whereFileMime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\FileUpload whereHashedFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\FileUpload whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\FileUpload wherePartId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\FileUpload whereTrueFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\FileUpload whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\FileUpload whereUploadedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\FileUpload withPaginate($withPaginate = false, $pageSize = 0)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\FileUpload notInUse()
 * @property string $box_number
 * @property string $part_name
 * @property bool $is_scanned
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\FileUpload whereBoxNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\FileUpload whereIsScanned($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\FileUpload wherePartName($value)
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\NeubusSrm\Models\Indexing\FileUpload onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\FileUpload whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\NeubusSrm\Models\Indexing\FileUpload withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\NeubusSrm\Models\Indexing\FileUpload withoutTrashed()
 * @property string|null $deletion_reason
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\FileUpload whereDeletionReason($value)
 * @property bool $is_deleted
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\FileUpload whereIsDeleted($value)
 */
class FileUpload extends Model implements NeuLoggableDetails
{
    use LegacySoftDeletes, UuidModelTrait,
        SoftDeletes {
        LegacySoftDeletes::runSoftDelete insteadof SoftDeletes;
        LegacySoftDeletes::restore insteadof SoftDeletes;
        LegacySoftDeletes::trashed insteadof SoftDeletes;
        LegacySoftDeletes::bootSoftDeletes insteadof SoftDeletes;
    }

    const DELETED_AT = 'is_deleted';

    protected $fillable = [
        'box_number',
        'part_name',
        'part_id',
        'uploaded_by',
        'true_file_name',
        'hashed_file_name',
        'current_fs_location',
        'file_mime',
        'is_scanned',
        'deletion_reason'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'deleted_at'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function uploadedBy() : BelongsTo {
        return $this->belongsTo(User::class, 'uploaded_by', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function part() : BelongsTo {
        return $this->belongsTo(Part::class, 'part_id', 'id');
    }

    /**
     * @param array $models
     * @return \Illuminate\Database\Eloquent\Collection|FileUploadCollection
     */
    public function newCollection(array $models = []) {
        parent::newCollection($models);
        return new FileUploadCollection($models);
    }

    /**
     * @param $query
     * @param bool $withPaginate
     * @param int $pageSize
     * @return mixed
     */
    public function scopeWithPaginate($query, bool $withPaginate = false, int $pageSize = 0) {
        if ($withPaginate) {
            return $query->paginate($pageSize);
        }
    }

    /**
     * @param Collection $arguments
     * @return string
     */
    public function getDetailsForNeuLog(Collection $arguments): string {
        $strArr = [
            'message' => sprintf('%s', $arguments->get('message')),
            'fields' => [],
            'responsible_table' => $this->getTable(),
            'record_id' => (string) $this->id,
            'company_id' => $arguments->get('company_id')
        ];
        if ($arguments->get('reason') !== null){
            $strArr['fields']['reason'] = sprintf('Reason: %s', $arguments->get('reason'));
        }
        if ($arguments->get('zip_file_name') !== null) {
            $strArr['fields']['zip_file_name'] = sprintf('Zip File Name : %s', $arguments->get('zip_file_name'));
        }
        if ($arguments->get('file_name') !== null) {
            $strArr['fields']['file_name'] = sprintf('File Name : %s', $arguments->get('file_name'));
        }
        if ($arguments->get('box_number') !== null) {
            $strArr['fields']['box_number'] = sprintf('Box Number : %s', $arguments->get('box_number'));
        }
        if ($arguments->get('part_name') !== null) {
            $strArr['fields']['part_name'] = sprintf('Part Name : %s', $arguments->get('part_name'));
        }
        if ($arguments->get('upload_method') !== null) {
            $strArr['fields']['upload_method'] = sprintf('Upload Method : %s', $arguments->get('upload_method'));
        }
        if ($arguments->get('request_name') !== null) {
            $strArr['fields']['request_name'] = sprintf('Request No : %s', $arguments->get('request_name'));
        }
        return json_encode($strArr);
    }
}
