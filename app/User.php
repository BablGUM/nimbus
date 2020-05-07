<?php

namespace App;


use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
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
        'password',
        'role_id',
        'verefi_code',
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function role()
    {
        return $this->hasMany('App\Role');
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
        $this->api_token = Auth::user()->createToken('token')->accessToken;
        return $this->api_token;
    }
    /**
     * Удаление токена
     * @return string  api_token;
     */
    public function removeToken()
    {
        $this->api_token = Auth::user()->token()->revoke();
        return $this->api_token;
    }

    public function toSendEmailLink($request,$link)
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
