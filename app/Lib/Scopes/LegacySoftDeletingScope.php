<?php
/**
 * User: mlawson
 * Date: 11/30/17
 * Time: 10:02 AM
 */

namespace NeubusSrm\Lib\Scopes;


use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class LegacySoftDeletingScope
 * @package NeubusSrm\Lib\Scopes
 */
class LegacySoftDeletingScope extends SoftDeletingScope {

	/**
	 * Updated for legacy purposes NSN-1158
	 * @param Builder $builder
	 * @param Model $model
	 */
	public function apply(Builder $builder, Model $model)
	{
		$builder->where($model->getQualifiedDeletedAtColumn(),'=', false);
	}

	/**
	 * Updated for legacy purposes NSN-1158
	 * Extend the query builder with the needed functions.
	 *
	 * @param  \Illuminate\Database\Eloquent\Builder  $builder
	 * @return void
	 */
	public function extend(Builder $builder)
	{
		foreach ($this->extensions as $extension) {
			$this->{"add{$extension}"}($builder);
		}
		$builder->onDelete(function (Builder $builder) {
			$column = $this->getDeletedAtColumn($builder);
			return $builder->update([
				$column => 1,
			'deleted_at' => $builder->getModel()->freshTimestampString()]);
		});
	}

	/**
	 * Updated for legacy purposes NSN-1158
	 * Get the "deleted at" column for the builder.
	 *
	 * @param  \Illuminate\Database\Eloquent\Builder  $builder
	 * @return string
	 */
	protected function getDeletedAtColumn(Builder $builder)
	{
		if (count($builder->getQuery()->joins) > 0) {
			return $builder->getModel()->getQualifiedDeletedAtColumn();
		} else {
			return $builder->getModel()->getDeletedAtColumn();
		}
	}

	/**
	 * Updated for legacy purposes NSN-1158
	 * Add the force delete extension to the builder.
	 *
	 * @param  \Illuminate\Database\Eloquent\Builder  $builder
	 * @return void
	 */
	protected function addForceDelete(Builder $builder)
	{
		$builder->macro('forceDelete', function (Builder $builder) {
			return $builder->getQuery()->delete();
		});
	}

	/**
	 * Updated for legacy purposes NSN-1158
	 * Add the restore extension to the builder.
	 *
	 * @param  \Illuminate\Database\Eloquent\Builder  $builder
	 * @return void
	 */
	protected function addRestore(Builder $builder)
	{
		$builder->macro('restore', function (Builder $builder) {
			$builder->withTrashed();

			return $builder->update([$builder->getModel()->getDeletedAtColumn() => false]);
		});
	}

	/**
	 * Updated for legacy purposes NSN-1158
	 * Add the with-trashed extension to the builder.
	 *
	 * @param  \Illuminate\Database\Eloquent\Builder  $builder
	 * @return void
	 */
	protected function addWithTrashed(Builder $builder)
	{
		$builder->macro('withTrashed', function (Builder $builder) {
			return $builder->withoutGlobalScope($this);
		});
	}

	/**
	 * Updated for legacy purposes NSN-1158
	 * Add the without-trashed extension to the builder.
	 *
	 * @param  \Illuminate\Database\Eloquent\Builder  $builder
	 * @return void
	 */
	protected function addWithoutTrashed(Builder $builder)
	{
		$builder->macro('withoutTrashed', function (Builder $builder) {
			$model = $builder->getModel();

			$builder->withoutGlobalScope($this)->where(
				$model->getQualifiedDeletedAtColumn(), '=', false
			);

			return $builder;
		});
	}

	/**
	 * Updated for legacy purposes NSN-1158
	 * Add the only-trashed extension to the builder.
	 *
	 * @param  \Illuminate\Database\Eloquent\Builder  $builder
	 * @return void
	 */
	protected function addOnlyTrashed(Builder $builder)
	{
		$builder->macro('onlyTrashed', function (Builder $builder) {
			$model = $builder->getModel();

			$builder->withoutGlobalScope($this)->where(
				$model->getQualifiedDeletedAtColumn(), '=', true
			);

			return $builder;
		});
	}

}