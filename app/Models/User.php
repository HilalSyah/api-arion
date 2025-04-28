<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'user';
    protected $fillable = ['username','email','password','role','google_id','avatar'];

    public function payments()
    {
        return $this->hasMany(Payment::class, 'id_user');
    }

    public function membership()
    {
        return $this->hasMany(MembershipUser::class, 'id_user');
    }
}
