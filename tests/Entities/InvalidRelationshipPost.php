<?php

namespace Tests\Entities;

use Leitsch\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvalidRelationshipPost extends Model
{
    use SoftDeletes;
    use CascadeSoftDeletes;

    public $dates = ['deleted_at'];

    protected $table = 'posts';

    protected $cascadeDeletes = ['comments', 'invalidRelationship', 'anotherInvalidRelationship'];

    protected $fillable = ['title', 'body'];

    public function comments()
    {
        return $this->hasMany('Tests\Entities\Comment', 'post_id');
    }

    public function invalidRelationship()
    {
        return;
    }

    public function anotherInvalidRelationship()
    {
        return;
    }
}
