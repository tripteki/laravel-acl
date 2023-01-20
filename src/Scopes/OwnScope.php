<?php

namespace Tripteki\ACL\Scopes;

use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class OwnScope
{
    use AuthorizesRequests;

    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return string
     */
    public static function space($model)
    {
        return Str::plural(Str::replace("\\", "_", Str::lower(get_class($model))));
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return array
     */
    public function scope($model)
    {
        $resource = static::space($model);
        $action = collect($this->resourceAbilityMap())->only(config("permission.own_resources"));
        $target = $model->{$model->getKeyName()};

        if (config("permission.enable_wildcard_permission")) {

            $able = $action->implode(",");

            return [

                $resource.".".$able.".".$target,
            ];

        } else {

            return $action->map(function ($able) use ($resource, $target) {

                return $resource.".".$able.".".$target;
    
            })->toArray();
        }
    }
};
