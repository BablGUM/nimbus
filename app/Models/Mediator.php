<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Order;

/**
 * Class Mediator
 * @package App\Models
 * @property int order_id
 * @property int user_id
 */
class Mediator extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'order_id',
        'user_id',
    ];

    public function request()
    {
        return $this->hasMany('App\Models\Order','id','request_id');
    }



}
