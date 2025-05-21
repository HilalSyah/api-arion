<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MembershipUser extends Model
{
    protected $table = 'membership_user';
    protected $fillable = ['id_user', 'id_pembayaran', 'tanggal_mulai', 'tanggal_berakhir', 'jenis_membership', 'harga','status','kode_barcode'];

    // Relasi ke Payment (karena tiap membership_user terkait dengan satu pembayaran)
    public function payment()
    {
        return $this->belongsTo(Payment::class, 'id_pembayaran');
    }

    // Relasi ke User (karena tiap membership_user terkait dengan satu user)
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
