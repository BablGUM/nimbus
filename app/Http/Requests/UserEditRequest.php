<?php


namespace App\Http\Requests;


class UserEditRequest extends ApiRequest
{


    public function rules()
    {
        return [

            "last_name" => 'required',
            'first_name' => 'required',
            "phone" => 'required',
            "residence_address" => 'required',
            'login' => 'required|unique:users|min:3|max:55'


        ];
    }


}