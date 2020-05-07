<?php

namespace App\Http\Controllers;


use App\Traits\ApiResponse;
use App\User;
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


    public function generateArrayRequest($request)
    {

        $code = \Illuminate\Support\Str::random(32);


        $data = [
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'login' => $request->login,
            'role_id' => $request->role_id,
            'verefi_code' => $code,

        ];
        return $data;
    }


    public function messageUser($request, $link, $code)
    {

        Mail::send(['html' => 'mail'], ['name' => $request->login, 'link' => $link, 'code' => $code],
            function ($message) use ($request, $link) {
                $message->to($request->email, $request->email)->subject('Подтвердите ваш адрес эл-почты');
                $message->from('technical.platformss@gmail.com', 'Technical Platform');

            });
    }

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

    public function checkUniqueLoginUser($request)
    {
        $flag = true;

        if(User::where('login', '=' , $request)->get()->count() > 0){
            $flag = false;
        }

        return $flag;
    }

    public function checkUniqueEmailUser($request)
    {
        $flag = true;
        if(User::where('email', '=' , $request)->get()->count() > 0){
            $flag = false;
        }
        return $flag;

    }
}
