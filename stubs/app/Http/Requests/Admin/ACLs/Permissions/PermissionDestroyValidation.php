<?php

namespace App\Http\Requests\Admin\ACLs\Permissions;

use Tripteki\ACL\Providers\ACLServiceProvider;
use Tripteki\Helpers\Http\Requests\FormValidation;
use Illuminate\Support\Facades\Auth;

class PermissionDestroyValidation extends FormValidation
{
    /**
     * @return void
     */
    protected function preValidation()
    {
        return [

            "permission" => $this->route("permission"),
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
        $ownable = Auth::check() && Auth::user()->hasRole(ACLServiceProvider::$SUPERADMIN) ? "|" : "|not_regex:/models/|";

        return [

            "permission" => "required|string".$ownable."exists:".config("permission.models.permission").",name",
        ];
    }
};
