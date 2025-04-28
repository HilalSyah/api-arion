<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Register extends Model
{
    protected $table = 'user';
    protected $fillable = ['username','email','password','role'];
}
