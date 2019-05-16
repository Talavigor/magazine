<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImgCategory extends Model
{
    protected $fillable = [
        'category_id',
        'title',
        'path',
        'name',
        'order',
    ];

    public function category()
    {
        return $this->belongsTo('App\Models\Category', 'category_id');
    }
}
