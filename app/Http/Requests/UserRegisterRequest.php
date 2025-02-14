<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRegisterRequest extends FormRequest
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
                'min:3',
                Rule::unique('users', 'username'),
            ],
            "mobile" => [
                'required',
                'string',
                'max:20',
                'min:10',
                Rule::unique('users', 'mobile'),
            ],
            "password" => [
                'required',
                'confirmed',
                'string',
                'min:6',
                'max:255',
            ]
        ];
    }


    public function getUsername(): string
    {
        return $this->validated('username');
    }

    public function getMobile(): string
    {
        return $this->validated('mobile');
    }

    public function getPassword(): string
    {
        return $this->validated('password');
    }
}
