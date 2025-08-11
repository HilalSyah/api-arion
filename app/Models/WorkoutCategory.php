<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkoutCategory extends Model
{
     protected $fillable = [
        'name',
        'image',
    ];

    public function levels()
    {
        return $this->hasMany(WorkoutLevel::class);
    }
}
