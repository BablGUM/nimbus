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

            'last_name' => 'required|min:2|max:50',
            'first_name' => 'required|min:2|max:50',
            'patronymic' => 'min:2|max:50',
            'password' =>  'required|confirmed|min:6|max:16',
            'password_confirmation' => 'required|min:6|max:16',
            'email' => 'required|email|unique:users|min:5|max:50',
            'login' => 'required|unique:users|min:3|max:55'


        ];
    }
}
