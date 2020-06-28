<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Category extends Model
{

    protected $fillable = [
        'name',

    ];

    public function role()
    {
        return $this->hasMany('App\Models\Order');
    }
}