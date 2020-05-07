<?php

namespace App\Http\Requests;


use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;


class ApiRequest  extends FormRequest
{

    /**
     * @param Validator $validator
     * @throws HttpResponseException
     */

    protected function failedValidation(Validator $validator)
    {
       $data = $validator->errors();
        throw new HttpResponseException(response()->json($data, 422));
    }

    public function attributes()
    {
        return [
            'name' => 'имя',
            'password' => 'пароль',
            'password_confirmation' => 'повторите пароль',
            'login' => 'имя пользователя'
        ];
    }

    /**
     * Возращает массив сообщений при ошибках
     *
     * @return array
     */

    public function messages()
    {
        return [
            'unique' => 'Пользователь с таким :attribute уже существует',
            'required' => 'Поле :attribute является обязательным для ввода',
            'between' => 'Поле :attribute должно содержать минимум :min  и максимум :max символов',
            'min' => 'Поле :attribute должно содержать минимум :min символа(ов)',
            'max' => 'Поле :attribute должно содержать максимум :max символа(ов)',
            'confirmed' => 'Пароли должны совпадать',
            'email' => 'Такой электронной почты не существует',
            'alpha_dash' => 'Вы ввели запрещёные символы',
            'filter_var' => 'Такой почты не существует',
            'required.role' => 'Пожалуйста выберите одну из ролей',
        ];
    }
}
