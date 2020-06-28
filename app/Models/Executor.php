<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * Class Executor
 * @package App\Models
 * @property int order_id
 * @property int user_id
 * @property boolean client_chose
 * @property boolean executor_chose
 */
class Executor extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'user_id',
        'client_chose',
        'executor_chose'
    ];

    public function request()
    {
        return $this->hasMany('App\Models\Order','id','order_id');
    }


}