<?php

namespace Leitsch\Database\Support;

use Exception;
use Illuminate\Support\Str;

class CascadeSoftDeleteException extends Exception
{
    public static function softDeleteNotImplemented($class): static
    {
        return new static(sprintf('%s does not implement Illuminate\Database\Eloquent\SoftDeletes', $class));
    }

    public static function invalidRelationships($relationships): static
    {
        return new static(sprintf(
            '%s [%s] must exist and return an object of type Illuminate\Database\Eloquent\Relations\Relation',
            Str::plural('Relationship', count($relationships)),
            join(', ', $relationships)
        ));
    }
}
