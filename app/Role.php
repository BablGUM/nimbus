<?php


namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{

    protected $fillable = [
        'role_name',

    ];

    protected $hidden = [
        'id',


    ];

    public function role()
    {
        return $this->hasMany('App\User');
    }
}