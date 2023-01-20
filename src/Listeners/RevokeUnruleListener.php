<?php

namespace Tripteki\ACL\Listeners;

use Tripteki\ACL\Events\Deleted;
use Tripteki\ACL\Scopes\OwnScope;
use Illuminate\Contracts\Queue\ShouldQueue as QueueableContract;
use Illuminate\Queue\InteractsWithQueue as QueueInteractionTrait;
use Tripteki\ACL\Contracts\Repository\Admin\IACLRoleRepository;
use Tripteki\ACL\Contracts\Repository\Admin\IACLPermissionRepository;
use Tripteki\ACL\Contracts\Repository\IACLRepository;

class RevokeUnruleListener implements QueueableContract
{
    use QueueInteractionTrait;

    /**
     * @var bool
     */
    public $afterCommit = true;

    /**
     * @var \Tripteki\ACL\Contracts\Repository\Admin\IACLRoleRepository
     */
    protected $role;

    /**
     * @var \Tripteki\ACL\Contracts\Repository\Admin\IACLPermissionRepository
     */
    protected $permission;

    /**
     * @var \Tripteki\ACL\Contracts\Repository\IACLRepository
     */
    protected $acl;

    /**
     * @var \Tripteki\ACL\Scopes\OwnScope
     */
    protected $own;

    /**
     * @param \Tripteki\ACL\Contracts\Repository\Admin\IACLRoleRepository $role
     * @param \Tripteki\ACL\Contracts\Repository\Admin\IACLPermissionRepository $permission
     * @param \Tripteki\ACL\Contracts\Repository\IACLRepository $acl
     * @param \Tripteki\ACL\Scopes\OwnScope $own
     * @return void
     */
    public function __construct(IACLRoleRepository $role, IACLPermissionRepository $permission, IACLRepository $acl, OwnScope $own)
    {
        $this->role = $role;
        $this->permission = $permission;
        $this->acl = $acl;

        $this->own = $own;
    }

    /**
     * @param \Tripteki\ACL\Events\Deleted $event
     * @return void
     */
    public function handle(Deleted $event)
    {
        $this->acl->setUser($event->user);

        if ($this->acl->getUser()) {

            foreach ($this->own->scope($event->model) as $able) {

                $this->acl->revoke($able);
                $this->permission->unrule($able);
            }
        }
    }
};
