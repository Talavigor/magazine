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
        'img',
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

    public function img_categories()
    {
        return $this->hasMany(ImgCategory::class, 'category_id');
    }
}
