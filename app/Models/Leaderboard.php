<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Leaderboard extends Model
{
    protected $table = 'leaderboard';
    protected $fillable = ['id_user','points'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
