<?php


namespace NeubusSrm\Lib\Traits;


use Carbon\Carbon;
use NeubusSrm\Lib\Scopes\LegacySoftDeletingScope;
/**
 * Trait LegacySoftDeletes
 * @package NeubusSrm\Lib\Traits\
 */
trait LegacySoftDeletes
{

    public function runSoftDelete() {
        $query = $this->newQueryWithoutScopes()->where($this->getKeyName(), $this->getKey());

        $this->{$this->getDeletedAtColumn()} = $deleted = true;

        $query->update([$this->getDeletedAtColumn() => $deleted]);

    }

    public function restore() {

        if ($this->fireModelEvent('restoring') === false) {
            return false;
        }

        $this->{$this->getDeletedAtColumn()} = false;
        $this->deleted_at = null;
        $this->exists = true;

        $result = $this->save();

        $this->fireModelEvent('restored', false);

        return $result;
    }

    public function trashed() {
        return $this->{$this->getDeletedAtColumn()};
    }

    public static function bootSoftDeletes()
    {
        static::addGlobalScope(new LegacySoftDeletingScope);
    }

}
