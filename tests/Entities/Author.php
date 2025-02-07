<?php

namespace Tests\Entities;

use Leitsch\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Author extends Model
{
    use SoftDeletes;
    use CascadeSoftDeletes;

    public $dates = ['deleted_at'];

    protected $cascadeDeletes = ['posts', 'posttypes'];

    protected $fillable = ['name'];

    public function posts()
    {
        return $this->hasMany('Tests\Entities\Post');
    }

    public function posttypes()
    {
        return $this->belongsToMany('Tests\Entities\PostType', 'authors__post_types', 'author_id', 'posttype_id');
    }
}
