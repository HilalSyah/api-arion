<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Details extends Model
{
    protected $fillable = ['item_id', 'nama', 'jumlahKandunganPer', 'manfaat', 'additionalNote', 'image'];

   public function item()
    {
        return $this->belongsTo(Items::class, 'item_id');
    }
}
