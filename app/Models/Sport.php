<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sport extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
      'sport_name',
    ];

    public function programmes(){
      return $this->hasMany('App\Models\Programme');
    }
}
