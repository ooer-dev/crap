<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReactionRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'trigger' => 'string|max:255',
            'response' => 'string',

            'contains_anywhere' => 'boolean',
            'delete_trigger' => 'boolean',
            'dm_response' => 'boolean',
        ];
    }
}
