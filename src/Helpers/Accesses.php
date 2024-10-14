<?php

use Tripteki\Helpers\Contracts\AuthModelContract;
use Tripteki\ACL\Contracts\Repository\IACLRepository;
use Tripteki\ACL\Traits\RolePermissionTrait;
use Illuminate\Support\Facades\Auth;

if (! function_exists("accesses"))
{
    /**
     * @param \Illuminate\Database\Eloquent\Model|null $user
     * @param bool $withOwn
     * @return array
     */
    function accesses(\Illuminate\Database\Eloquent\Model|null $user = null, $withOwn = true)
    {
        $accesses = [];

        $class = get_class(app(AuthModelContract::class));
        $repository = app(IACLRepository::class);

        if ($user) {

            $repository->setUser($user);

        } else {

            if (Auth::check()) {

                $repository->setUser(Auth::user());
            }
        }

        if ($repository->getUser() instanceof $class && in_array(RolePermissionTrait::class, class_uses($class))) {

            if ($withOwn) $accesses = array_merge($repository->permissions()->toArray(), $repository->owns()->toArray());
            else $accesses = $repository->permissions()->toArray();
        }

        return $accesses;
    };
}
