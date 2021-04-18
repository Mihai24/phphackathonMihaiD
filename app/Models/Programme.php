<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Programme extends Model
{
    use HasFactory;

    public $timestamps = false;

    public int $room;

    protected $fillable = [
        'program_name',
        'participants',
        'start_at',
        'end_at',
        'room'
    ];

    public function appointments(){
      return $this->hasMany('App\Models\Appointment');
    }

}
