<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Role
 * @package App\Models
 * @property string role_name
 */
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
        return $this->hasMany('App\Models\User');
    }
}