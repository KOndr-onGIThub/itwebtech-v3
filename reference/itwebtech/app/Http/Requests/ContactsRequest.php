<?php

namespace App\Http\Requests;
use Illuminate\Validation\Rule;


use Illuminate\Foundation\Http\FormRequest;

class ContactsRequest extends FormRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => ['required', 'min:1'],
            'email' => ['required', 'email'],
            'privacypolicy' => ['accepted'],
            'g-recaptcha-response' => 'required|captcha',
            'tel' => ['required', 'min:9']
            /* 'message' => ['required', 'min:50'], */
/*             'year' => [
                'required',
                Rule::in(date('Y')),
            ], */
        ];

    }
}
