<?php

namespace App\Http\Requests\Admin\ACLs\Roles;

use Tripteki\ACL\Providers\ACLServiceProvider;
use Tripteki\Helpers\Http\Requests\FormValidation;

class RoleDestroyValidation extends FormValidation
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

            "role" => "required|string|not_regex:/^".ACLServiceProvider::$SUPERADMIN."$/|exists:".config("permission.models.role").",name",
        ];
    }
};
