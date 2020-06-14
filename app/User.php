<?php

namespace App;


use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    public $timestamps = false;

    protected $fillable = [
        'login',
        'email',
        'full_name',
        'first_name',
        'last_name',
        'patronomyc',
        'phone',
        'residence_address',
        'password',
        'role_id',
        'verefi_code',
    ];


    protected $hidden = [
        'password',
        'api_token',
        'reset_code',
        'verefi_code',
        'is_admin',

    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function role()
    {
        return $this->hasMany('App\Role','id','role_id');
    }

    /**
     * Проверка пользователя на админа возращает true или false
     * @return bool is_admin;
     */
    public function isAdmin()

    {
        return $this->is_admin;
    }
    /**
     * Генерация токена
     * @return string  api_token;
     */
    public function generateToken()
    {
        $this->api_token = $token = Str::random(60);
        Auth::user()->api_token = $this->api_token;
        Auth::user()->save();
        return $this->api_token;
    }
    /**
     * Удаление токена
     * @return string  api_token;
     */
    public function removeToken()
    {
        Auth::user()->api_token = null;
        Auth::user()->save();
        return $this->api_token;
    }
    /**
     * Отправка сообщения для восстановление пароля
     * @param $request,$link
     *
     * @return null
     *
     */
    public function toSendEmailLink($request, $link)
    {
        $request->reset_code = \Illuminate\Support\Str::random(32);
        $request->save();
        $link = $link . '?reset_code=' . $request->reset_code;

        Mail::send(['html' => 'reset'], ['name' => $request->login, 'link' => $link, 'code' => $request->reset_code],
            function ($message) use ($request, $link) {
                $message->to($request->email, $request->email)->subject('Восстановление пароля');
                $message->from('technical.platformss@gmail.com', 'Technical Platform');

            });

    }




}
