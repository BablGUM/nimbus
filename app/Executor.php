<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class Executor extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'request_id',
        'user_id',
        'client_chose',
        'executor_chose'
    ];

    public function request()
    {
        return $this->hasMany('App\Application','id','request_id');
    }


}