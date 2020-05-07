<?php
    /**
     * Created by PhpStorm.
     * User: я
     * Date: 30.04.2020
     * Time: 15:40
     */

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{

    protected $fillable = [
        'role_name',

    ];

    public function role()
    {
        return $this->hasMany('App\User');
    }
}