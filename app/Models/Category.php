<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Encore\Admin\Traits\AdminBuilder;
use Encore\Admin\Traits\ModelTree;
use Illuminate\Support\Str;

class Category extends Model
{
    use ModelTree, AdminBuilder;


    protected $fillable = [
        'parent_id',
        'title',
        'order',
        'slug',
        'description',
        'imgg',
        'active',
    ];

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    public function imgs()
    {
        return $this->hasMany('App\Models\Img', 'category_id');
    }

    public function img_categories()
    {
        return $this->hasMany('App\Models\ImgCategory', 'category_id');
    }
}
