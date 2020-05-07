<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class Task extends Model
{
    protected $fillable = [
        'name_list',
        'status',
        'short_description',
        'urgency',
        'user_id',
        'task_list_id'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function task_list()
    {
        return $this->belongsTo('App\TaskList');
    }
}
