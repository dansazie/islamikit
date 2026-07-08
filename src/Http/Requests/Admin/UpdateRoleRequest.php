<?php

namespace Islamikit\Starterkit\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoleRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:roles,name,' . $this->role->id,
            'permissions' => 'array',
            'permissions.*' => 'string|exists:permissions,name',
        ];
    }
}