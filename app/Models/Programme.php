<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Programme extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'program_name',
        'participants',
        'start_at',
        'end_at',
        'room',
    ];

    public function room(){
      return $this->belongsTo('App\Models\Room');
    }

    public function sport(){
      return $this->belongsTo('App\Models\Sport');
    }

    public function appointments(){
      return $this->hasMany('App\Models\Appointment');
    }

}
