<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Application;

class Mediator extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'request_id',
        'user_id',
    ];

    public function request()
    {
        return $this->hasMany('App\Application','id','request_id');
    }



}
