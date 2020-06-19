<?php


namespace App\Http\Requests;


class UserEditRequest extends ApiRequest
{


    public function rules()
    {
        return [

            'last_name' => 'min:2|max:55',
            'first_name' => 'min:2|max:55',
            'patronymic' => 'min:2|max:55',
            "phone" => 'required',
            "residence_address" => 'required',
            'login' => 'required|min:3|max:55'


        ];
    }


}