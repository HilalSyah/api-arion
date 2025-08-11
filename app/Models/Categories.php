<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    protected $fillable = ['name', 'image'];

    public function items()
    {
        return $this->hasMany(Items::class, 'category_id');
    }

}
