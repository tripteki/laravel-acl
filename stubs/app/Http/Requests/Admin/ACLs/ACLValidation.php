<?php

namespace App\Http\Requests\Admin\ACLs;

use Illuminate\Validation\Rule;
use Tripteki\Helpers\Contracts\AuthModelContract;
use Tripteki\Helpers\Http\Requests\FormValidation;

class ACLValidation extends FormValidation
{
    /**
     * @const string
     */
    const GRANT_PERMISSIONS_TO_ROLE = "grant_permissions_to_role";

    /**
     * @const string
     */
    const REVOKE_PERMISSIONS_FROM_ROLE = "revoke_permissions_from_role";

    /**
     * @const string
     */
    const GRANT_ROLES_TO_USER = "grant_roles_to_user";

    /**
     * @const string
     */
    const REVOKE_ROLES_FROM_USER = "revoke_roles_from_user";

    /**
     * @return void
     */
    protected function preValidation()
    {
        return [

            "context" => $this->route("context"),
            "object" => $this->route("object"),
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
        $provider = app(AuthModelContract::class);

        $validator = [

            "context" => "required|string|in:".self::GRANT_PERMISSIONS_TO_ROLE.",".self::REVOKE_PERMISSIONS_FROM_ROLE.",".self::GRANT_ROLES_TO_USER.",".self::REVOKE_ROLES_FROM_USER,
            "rules" => "required|array",
            "rules.*" => [],
            "object" => [ "required", "string", ],
        ];

        if ($this->route("context") == self::GRANT_PERMISSIONS_TO_ROLE || $this->route("context") == self::REVOKE_PERMISSIONS_FROM_ROLE) {

            $validator["rules.*"] = [

                "not_regex:/models/",
                Rule::exists(config("permission.models.permission"), "name"),
            ];

            $validator["object"][] = "exists:".config("permission.models.role").",name";

        } else if ($this->route("context") == self::GRANT_ROLES_TO_USER || $this->route("context") == self::REVOKE_ROLES_FROM_USER) {

            $validator["rules.*"] = [

                Rule::exists(config("permission.models.role"), "name"),
            ];

            $validator["object"][] = "exists:".get_class($provider).",".keyName($provider);
        }

        return $validator;
    }
};
