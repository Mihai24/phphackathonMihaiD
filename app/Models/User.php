<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public $timestamps = false;

    protected $role_id = 1;

    protected $fillable = [
        'name',
        'cnp',
        'role_id',
    ];

    public function appointments(){
      return $this->hasMany('App\Models\Booking');
    }

    public function role(){
      return $this->belongsTo('App\Models\Role');
    }

}
