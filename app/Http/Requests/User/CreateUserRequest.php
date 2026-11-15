<?php

namespace App\Http\Requests\User;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->tokenCan('SYSTEM:MANAGE_USERS');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->whereNull('deleted_at')
            ],
            'password' => ['required', 'string', 'min:8', 'max:128'],
            'password_confirmation' => ['required', 'string', 'min:8', 'same:password'],
            'must_change_password' => ['sometimes', 'boolean'],
            'roles' => ['required','array', 'min:1'],
            'roles.*.role_id' => [
                'required',
                'integer',
                'distinct',
                Rule::exists('roles', 'id')->whereNull('deleted_at'),
            ],
            'roles.*.is_active' => ['sometimes', 'boolean'],
            'roles.*.start_date' => ['nullable','date'],
            'roles.*.end_date' =>  ['nullable', 'date', 'after_or_equal:roles.*.start_date'],
        ];
    }
}
