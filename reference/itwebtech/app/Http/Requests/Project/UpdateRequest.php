<?php

namespace App\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'min:3', 'max:20'],
            'description' => ['required', 'min:25', 'max:255'],
            'content' => ['required', 'min:50'],
        ];
    }
}
