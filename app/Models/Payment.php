<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payment';

    protected $fillable = [
        'id_user', 'id_membership', 'date', 'email', 'price', 'metode_pembayaran'
    ];

    public function membership()
    {
        return $this->belongsTo(Membership::class, 'id_membership');
    }

    public function memberships()
    {
        return $this->hasMany(MembershipUser::class, 'id_pembayaran');
    }
}
