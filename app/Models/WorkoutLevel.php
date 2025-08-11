<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkoutLevel extends Model
{
    protected $fillable = ['workout_category_id', 'name', 'sets_reps'];

    public function category()
    {
        return $this->belongsTo(WorkoutCategory::class, 'workout_category_id');
    }

    public function exercises()
    {
        return $this->hasMany(WorkoutExercise::class);
    }
}
