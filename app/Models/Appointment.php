<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
      'user_id',
      'programme_id',
    ];

    public function programmes(){
      $this->belongsToMany('App\Models\Programme');
    }

    public function users(){
      $this->belongsToMany('App\Models\User');
    }

}
