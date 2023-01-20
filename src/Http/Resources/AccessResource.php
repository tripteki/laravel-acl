<?php

namespace Tripteki\ACL\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\Resources\Json\JsonResource;

class AccessResource extends JsonResource
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $accesses = explode(".", $this->resource["name"]);
        $resources = explode(",", $accesses[0]);
        $actions = explode(",", $accesses[1]);
        $targets = explode(",", $accesses[2]);

        $ables = [];

        foreach ($resources as $resource) {

            foreach ($actions as $action) {

                foreach ($targets as $target) {

                    $ables[$resource][$target][$this->resource["id"]][] = $action;
                }
            }
        }

        return $ables;
    }
};
