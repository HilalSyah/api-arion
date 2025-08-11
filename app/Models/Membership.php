<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    protected $table = 'membership';
    protected $fillable = ['type','price','durasi','satuan','status'];

    public function payments()
    {
        return $this->hasMany(Payment::class, 'id_membership');
    }

    public function membershipUsers()
    {
        return $this->hasMany(MembershipUser::class, 'id_membership');
    }
}
