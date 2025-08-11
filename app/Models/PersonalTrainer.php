<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersonalTrainer extends Model
{
    protected $table = 'personal_trainer';
    protected $fillable = ['name','sesi','harga'];
}
