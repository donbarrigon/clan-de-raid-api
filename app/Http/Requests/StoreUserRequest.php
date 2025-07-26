<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            'name' => 'required|string|unique:users,name|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => 'required|string|min:8|confirmed',
            'full_name' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:20|regex:/^[0-9\-\+]+$/',
            'discord_username' => 'nullable|string|max:50|regex:/^[a-zA-Z0-9_]+$/',
            'city_id' => 'required|exists:cities,id',
            'preferences' => 'nullable|json',
        ];
    }
}
