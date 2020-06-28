<?php

namespace App\Http\Controllers;


use App\Traits\ApiResponse;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, ApiResponse;

    /**
     * Генерация массива данных для регистрации
     *
     * @param  $request
     *
     * @return array
     *
     *
     */
    public function generateArrayRequest($request)
    {

        $code = \Illuminate\Support\Str::random(32);


        $data = [
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'login' => $request->login,
            'role_id' => $request->role_id,
            'verefi_code' => $code,
            'full_name' => $request->last_name . " " .$request->first_name . " " . $request->patronymic,
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'patronymic'  => $request->patronymic,
            'phone' => $request->phone,

        ];
        return $data;
    }

    /**
     * Генерация сообщения для регистрации данных для регистрации
     *
     * @param  $request
     * @param  $link
     * @param  $code
     *
     * @return null
     *
     *
     */
    public function messageUser($request, $link, $code)
    {

        Mail::send(['html' => 'mail'], ['name' => $request->login, 'link' => $link, 'code' => $code],
            function ($message) use ($request, $link) {
                $message->to($request->email, $request->email)->subject('Подтвердите ваш адрес эл-почты');
                $message->from('technical.platformss@gmail.com', 'Строительная Биржа «Строитель.ру»');

            });
    }
    /**
     * Проверка подтверждения почты
     *
     * @param  $response
     *
     *
     * @return boolean
     *
     *
     */
    public function verificationUserCheck($response)
    {
        $verifed_email = [
            'verifed_email' => true,
            'verefi_code' => null,
        ];

        if ($response->get()->count() > 0) {
            $response->update($verifed_email);
            return true;
        } else {
            return false;

        }

    }
    /**
     * Проверка уникальности логина
     *
     * @param  $request
     *
     *
     * @return boolean
     *
     *
     */
    public function checkUniqueLoginUser($request)
    {
        $flag = true;

        if (User::where('login', '=', $request)->get()->count() > 0) {
            $flag = false;
        }

        return $flag;
    }
    /**
     * Проверка уникальности почты
     *
     * @param  $request
     *
     *
     * @return boolean
     *
     *
     */
    public function checkUniqueEmailUser($request)
    {
        $flag = true;
        if (User::where('email', '=', $request)->get()->count() > 0) {
            $flag = false;
        }
        return $flag;

    }



 // функция ниже для хероку
    public function check()
    {
        return \response()->json('ok', 200);
    }
}
