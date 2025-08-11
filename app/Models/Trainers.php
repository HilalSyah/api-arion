<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trainers extends Model
{
    protected $table = 'list_trainers';
    protected $fillable = ['name','gender','phone'];
}
