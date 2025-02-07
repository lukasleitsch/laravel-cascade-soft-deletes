<?php

namespace Tests\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Leitsch\Database\Support\CascadeSoftDeletes;

class Post extends Model
{
    use SoftDeletes;
    use CascadeSoftDeletes;

    public $dates = ['deleted_at'];

    protected $cascadeDeletes = ['comments', 'postType'];

    protected $fillable = ['title', 'body'];

    public function comments()
    {
        return $this->hasMany('Tests\Entities\Comment');
    }

    public function postType()
    {
        return $this->hasOne('Tests\Entities\PostType', 'post_id');
    }
}
