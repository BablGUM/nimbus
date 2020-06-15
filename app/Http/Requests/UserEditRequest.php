<?php


namespace App\Http\Requests;


class UserEditRequest extends ApiRequest
{


    public function rules()
    {
        return [

            'last_name' => 'required|min:2|max:50',
            'first_name' => 'required|min:2|max:50',
            'patronomyc' => 'min:2|max:50',
            "phone" => 'required',
            "residence_address" => 'required',
            'login' => 'required|unique:users|min:3|max:55'


        ];
    }


}