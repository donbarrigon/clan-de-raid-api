<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGameAccountRequest extends FormRequest
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
            'user_id' => ['required', 'exists:users,id'],
            'clan_id' => ['nullable', 'exists:clans,id'],
            'plarium_id' => ['required', 'string', 'unique:game_accounts,plarium_id'],
            'player_name' => ['required', 'string', 'max:255'],
            'role' => ['required', 'in:leader,deputy,officer,member'],
            'stats' => ['nullable', 'json'],
            'type' => ['required', 'in:main,secondary,chinese account,friends program'],
        ];
    }
}
