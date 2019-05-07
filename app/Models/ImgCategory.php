<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImgCategory extends Model
{
    protected $fillable = [
        'category_id',
        'title',
        'path',
        'alt',
    ];

    public function categories()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
