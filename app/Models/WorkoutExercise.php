<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkoutExercise extends Model
{
    protected $fillable = ['workout_level_id', 'name', 'reps', 'note', 'image'];

    public function level()
    {
        return $this->belongsTo(WorkoutLevel::class, 'workout_level_id');
    }
}
