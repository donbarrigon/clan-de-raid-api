<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClanRequirementRequest extends FormRequest
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
            'clan_id' => ['required', 'exists:clans,id'],
            'label' => ['nullable', 'string', 'max:255'],
            'min_score' => ['required', 'integer', 'min:0'],
            'priority' => ['required', 'in:low,medium,high,critical'],
            'description' => ['nullable', 'string'],
        ];
    }
}
