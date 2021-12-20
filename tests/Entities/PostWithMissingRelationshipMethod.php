<?php

namespace Tests\Entities;

use Leitsch\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostWithMissingRelationshipMethod extends Model
{
    use SoftDeletes;
    use CascadeSoftDeletes;

    public $dates = ['deleted_at'];

    protected $table = 'posts';

    protected $cascadeDeletes = 'comments';

    protected $fillable = ['title', 'body'];
}
