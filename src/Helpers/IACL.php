<?php

use Illuminate\Support\Str;
use Tripteki\ACL\Scopes\OwnScope;

if (! function_exists("iacl"))
{
    /**
     * @param string $resource
     * @param string $action
     * @param int|string $target
     * @return string
     */
    function iacl($resource, $action, $target)
    {
        return OwnScope::space(app($resource)).".".Str::lower($action).".".$target;
    };
}
