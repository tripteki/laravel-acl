<?php

namespace App\Http\Requests\Admin\ACLs\Roles;

use Tripteki\Helpers\Http\Requests\FormValidation;

class RoleStoreValidation extends FormValidation
{
    /**
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [

            "role" => "required|string|max:127|regex:/^[0-9a-zA-Z\._\-]+$/|unique:".config("permission.models.role").",name",
        ];
    }
};
