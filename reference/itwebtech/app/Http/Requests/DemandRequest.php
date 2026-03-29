<?php

namespace App\Http\Requests;
use Illuminate\Validation\Rule;


use Illuminate\Foundation\Http\FormRequest;

class DemandRequest extends FormRequest
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
            'phone' => ['required', 'min:9'],
            'email' => ['required', 'email'],
            'privacypolicy' => ['accepted'],
            /* 'g-recaptcha-response' => 'required|captcha', */
            /* 'message' => ['required', 'min:50'], */
/*             'year' => [
                'required',
                Rule::in(date('Y')),
            ], */
        ];

    }
}
