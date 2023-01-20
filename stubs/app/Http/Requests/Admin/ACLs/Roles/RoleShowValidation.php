<?php

namespace App\Http\Requests\Admin\ACLs\Roles;

use Tripteki\Helpers\Http\Requests\FormValidation;

class RoleShowValidation extends FormValidation
{
    /**
     * @return void
     */
    protected function preValidation()
    {
        return [

            "role" => $this->route("role"),
        ];
    }

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

            "role" => "required|string|exists:".config("permission.models.role").",name",
        ];
    }
};
