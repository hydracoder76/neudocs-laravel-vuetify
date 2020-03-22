<?php

namespace NeubusSrm\Models\Indexing;


use Illuminate\Database\Eloquent\Model;


/**
 * NeubusSrm\Models\Indexing\BoxTypeDef
 *
 * @property int $id
 * @property string $type
 * @property string $description
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\BoxTypeDef newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\BoxTypeDef newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\BoxTypeDef query()
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\BoxTypeDef whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\BoxTypeDef whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Indexing\BoxTypeDef whereType($value)
 * @mixin \Eloquent
 */
class BoxTypeDef extends Model
{

    protected $table = 'box_type_def';

    protected $fillable = [
        'type',
        'description'
    ];

}
