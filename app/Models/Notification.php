<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use App\Models\Order;
use Illuminate\Support\Facades\Mail;

class Notification extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'request_id',
        'user_id',
    ];

    public function requestResponded($email,$fio,$link,$name_app)
    {
        Mail::send(['html' => 'executor'], ['name' => $fio, 'link' => $link,'name_app' => $name_app],
            function ($message) use ($email,$link,$name_app) {
                $message->to($email, $email)->subject('На ваш заказ откликнулись');
                $message->from('technical.platformss@gmail.com', 'Строительная Биржа «Строитель.ру»');

            });
    }

    public function requestStart($email,$fio,$link,$name_app)
    {
        Mail::send(['html' => 'start'], ['name' => $fio, 'link' => $link,'name_app' => $name_app],
            function ($message) use ($email,$link,$name_app) {
                $message->to($email, $email)->subject('Назначен Исполнитель на заказ');
                $message->from('technical.platformss@gmail.com', 'Строительная Биржа «Строитель.ру»');

            });
    }


}