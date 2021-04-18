<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
      'user_id',
      'programme_id',
    ];

    public function programme(){
      $this->belongsTo('App\Models\Programme');
    }

    public function users(){
      $this->belongsToMany('App\Models\User');
    }

}
