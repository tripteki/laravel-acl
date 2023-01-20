<?php

namespace Tripteki\ACL\Events;

use Illuminate\Queue\SerializesModels as SerializationTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Deleted
{
    use SerializationTrait;

    /**
     * @var \Illuminate\Contracts\Auth\Authenticatable
     */
    public $user;

    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    public $model;

    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return void
     */
    public function __construct(Model $model)
    {
        $this->user = Auth::check() ? Auth::user() : null;
        $this->model = $model;
    }
};
