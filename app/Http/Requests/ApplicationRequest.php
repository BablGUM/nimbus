<?php

    namespace App\Http\Requests;

    use Illuminate\Foundation\Http\FormRequest;

    class ApplicationRequest extends ApiRequest
    {

        /**
         * Get the validation rules that apply to the request.
         *
         * @return array
         */
        public function rules()
        {
            return [
                'title' => 'required|max:100',
                'description' => 'required|max:2000',
                'budget' => 'required',
                'start_date' => 'required|date',
                'end_date' => 'required|date',
                'file' => 'mimes:docx,doc,dot,pdf|max:5120',
                'name_category' => 'required'

            ];
        }
    }
