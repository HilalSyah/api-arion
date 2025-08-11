<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Items extends Model
{
    protected $fillable = ['category_id', 'name', 'image'];

    public function details()
    {
        return $this->hasMany(Details::class, 'item_id');
    }

    public function category()
    {
        return $this->belongsTo(Categories::class, 'category_id');
    }

}
