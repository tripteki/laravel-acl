<?php

namespace Tripteki\ACL\Traits;

use Spatie\Permission\Traits\HasRoles;

trait RolePermissionTrait
{
    use HasRoles;

    /**
     * @return string
     */
    protected function getDefaultGuardName(): string
    {
        return (string) null;
    }
};
