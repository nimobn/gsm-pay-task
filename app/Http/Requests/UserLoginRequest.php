<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserLoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "username" => [
                'required',
                'string',
                'max:30',
            ],
            "password" => [
                'required',
                'string',
                'max:255',
            ]
        ];
    }


    public function getUsername(): string
    {
        return $this->validated('username');
    }

    public function getPassword(): string
    {
        return $this->validated('password');
    }
}
