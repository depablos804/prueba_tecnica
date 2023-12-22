<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    public function request() {
        return $this->hasOne(Request::class);
      }

      public function room() {
        return $this->belongsTo(Room::class);
      }
}
