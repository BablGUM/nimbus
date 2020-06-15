<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserAddRequest extends ApiRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [

            'last_name' => 'required',
            'first_name' => 'required',
            'password' =>  'required|confirmed|min:6|max:16|alpha_dash',
            'password_confirmation' => 'required|min:6|max:16|alpha_dash',
            'email' => 'required|email|unique:users|min:5|max:50',
            'login' => 'required|unique:users|min:3|max:55'


        ];
    }
}
