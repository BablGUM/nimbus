<?php
    /**
     * Created by PhpStorm.
     * User: я
     * Date: 19.06.2020
     * Time: 17:07
     */

namespace App\Http\Requests;


class ApplicationEditRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|max:255',
            'description' => 'required|max:2000',
            'budget' => 'required|int|min:1|max:1000000000',
            'file' => 'mimes:docx,doc,dot,pdf|max:2048',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ];
    }

}