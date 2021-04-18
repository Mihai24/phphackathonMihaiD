<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Programme extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'participants',
        'start_at',
        'end_at',
        'room_id',
        'sport_id',
    ];

    public function room(){
      return $this->belongsTo('App\Models\Room');
    }

    public function sport(){
      return $this->belongsTo('App\Models\Sport');
    }

    public function appointments(){
      return $this->hasMany('App\Models\Booking');
    }

}
