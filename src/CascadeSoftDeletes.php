<?php

namespace Leitsch\Database\Support;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use LogicException;

trait CascadeSoftDeletes
{
    /**
     * Boot the trait.
     *
     * Listen for the deleting event of a soft deleting model, and run
     * the delete operation for any configured relationship methods.
     *
     * @throws LogicException
     */
    protected static function bootCascadeSoftDeletes()
    {
        static::deleting(function ($model) {
            $model->validateCascadingSoftDelete();

            $model->runCascadingDeletes();
        });
    }

    /**
     * Validate that the calling model is correctly setup for cascading soft deletes.
     *
     * @throws CascadeSoftDeleteException
     */
    protected function validateCascadingSoftDelete(): void
    {
        if (! $this->implementsSoftDeletes()) {
            throw CascadeSoftDeleteException::softDeleteNotImplemented(get_called_class());
        }

        if ($invalidCascadingRelationships = $this->hasInvalidCascadingRelationships()) {
            throw CascadeSoftDeleteException::invalidRelationships($invalidCascadingRelationships);
        }
    }

    /**
     * Run the cascading soft delete for this model.
     */
    protected function runCascadingDeletes(): void
    {
        foreach ($this->getActiveCascadingDeletes() as $relationship) {
            $this->cascadeSoftDeletes($relationship);
        }
    }

    /**
     * Cascade delete the given relationship on the given mode.
     */
    protected function cascadeSoftDeletes(string $relationship): void
    {
        $delete = $this->forceDeleting ? 'forceDelete' : 'delete';

        foreach ($this->{$relationship}()->get() as $model) {
            $model->pivot ? $model->pivot->{$delete}() : $model->{$delete}();
        }
    }

    /**
     * Determine if the current model implements soft deletes.
     */
    protected function implementsSoftDeletes(): bool
    {
        return method_exists($this, 'runSoftDelete');
    }

    /**
     * Determine if the current model has any invalid cascading relationships defined.
     *
     * A relationship is considered invalid when the method does not exist, or the relationship
     * method does not return an instance of Illuminate\Database\Eloquent\Relations\Relation.
     */
    protected function hasInvalidCascadingRelationships(): array
    {
        return array_filter($this->getCascadingDeletes(), function ($relationship) {
            return ! method_exists($this, $relationship) || ! $this->{$relationship}() instanceof Relation;
        });
    }

    /**
     * Fetch the defined cascading soft deletes for this model.
     */
    protected function getCascadingDeletes(): array
    {
        return isset($this->cascadeDeletes) ? (array) $this->cascadeDeletes : [];
    }

    /**
     * For the cascading deletes defined on the model, return only those that are not null.
     */
    protected function getActiveCascadingDeletes(): array
    {
        return array_filter($this->getCascadingDeletes(), function ($relationship) {
            return $this->{$relationship}()->exists();
        });
    }
}
