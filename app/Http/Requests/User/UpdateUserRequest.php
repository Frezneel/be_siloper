<?php

namespace App\Http\Requests\User;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
        $userId = $this->route('user')?->id ?? $this->route('user');
        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'email' => [
                'sometimes',
                'required',
                'string',
                'email',
                'max:255',
                    Rule::unique('users', 'email')
                    ->ignore($this->route('user'))
                    ->whereNull('deleted_at'),
            ],
            'roles'                => ['sometimes', 'required', 'array', 'min:1'],
            'roles.*.role_id'      => [
                'required',
                'integer',
                'distinct',
                Rule::exists('roles', 'id')->whereNull('deleted_at'),
            ],
            'roles.*.is_active'    => ['sometimes', 'boolean'],
            'roles.*.start_date'   => ['nullable', 'date'],
            'roles.*.end_date'     => ['nullable', 'date', 'after_or_equal:roles.*.start_date'],
        ];
    }
}
