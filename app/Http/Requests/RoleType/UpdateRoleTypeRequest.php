<?php

namespace App\Http\Requests\RoleType;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRoleTypeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->tokenCan('SYSTEM:MANAGE_ROLES');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $roleTypeId = $this->route('id');

        return [
            'name' => 'sometimes','required','string','max:255','uppercase',
                Rule::unique('role_types', 'name')->ignore($roleTypeId),
            'description' => 'sometimes|string|max:255|nullable',
        ];
    } 
}
