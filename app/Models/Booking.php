<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
      // Nama tabel (jika tidak mengikuti default Laravel: "bookings")
    protected $table = 'bookings';

    // Field yang bisa diisi secara massal
    protected $fillable = [
        'user_id',
        'personal_trainer_id',
        'trainer_id',
        'price',
        'metode_pembayaran',
    ];

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke trainer
    public function trainer()
    {
        return $this->belongsTo(Trainers::class);
    }

    // Relasi ke paket personal trainer
    public function personalTrainer()
    {
        return $this->belongsTo(PersonalTrainer::class);
    }
}
