<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskList extends Model
{
    protected $fillable = [
        'name',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function task()
    {
        return $this->hasMany('App\Task');
    }
}
