<?php

namespace App\Http\Requests\Admin\ACLs\Permissions;

use Tripteki\Helpers\Http\Requests\FormValidation;

class PermissionStoreValidation extends FormValidation
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

            "permission" => "required|string|max:127|not_regex:/models/|regex:/^[0-9a-zA-Z\._\-]+$/|unique:".config("permission.models.permission").",name",
        ];
    }
};
