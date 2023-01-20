<?php

namespace App\Http\Requests\Admin\ACLs\Users;

use Tripteki\Helpers\Contracts\AuthModelContract;
use Tripteki\Helpers\Http\Requests\FormValidation;

class UserShowValidation extends FormValidation
{
    /**
     * @return void
     */
    protected function preValidation()
    {
        return [

            "user" => $this->route("user"),
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

        return [

            "user" => "required|string|exists:".get_class($provider).",".keyName($provider),
        ];
    }
};
