<?php

namespace Tripteki\ACL\Console\Commands;

use Tripteki\Helpers\Contracts\AuthModelContract;
use Tripteki\Helpers\Helpers\ProjectHelper;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Console\Command;

class InstallCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = "adminer:install:acl";

    /**
     * @var string
     */
    protected $description = "Install the acl stack";

    /**
     * @var \Tripteki\Helpers\Helpers\ProjectHelper
     */
    protected $helper;

    /**
     * @param \Tripteki\Helpers\Helpers\ProjectHelper $helper
     * @return void
     */
    public function __construct(ProjectHelper $helper)
    {
        parent::__construct();

        $this->helper = $helper;
    }

    /**
     * @return int
     */
    public function handle()
    {
        $this->installStack();

        return 0;
    }

    /**
     * @return int|null
     */
    protected function installStack()
    {
        (new Filesystem)->ensureDirectoryExists(base_path("routes/user"));
        (new Filesystem)->ensureDirectoryExists(base_path("routes/admin"));
        (new Filesystem)->copy(__DIR__."/../../../stubs/routes/user/acl.php", base_path("routes/user/acl.php"));
        (new Filesystem)->copy(__DIR__."/../../../stubs/routes/admin/acl.php", base_path("routes/admin/acl.php"));
        $this->helper->putRoute("api.php", "user/acl.php");
        $this->helper->putRoute("api.php", "admin/acl.php");

        (new Filesystem)->ensureDirectoryExists(app_path("Http/Controllers/ACL"));
        (new Filesystem)->copyDirectory(__DIR__."/../../../stubs/app/Http/Controllers/ACL", app_path("Http/Controllers/ACL"));
        (new Filesystem)->ensureDirectoryExists(app_path("Http/Requests/ACLs"));
        (new Filesystem)->copyDirectory(__DIR__."/../../../stubs/app/Http/Requests/ACLs", app_path("Http/Requests/ACLs"));
        (new Filesystem)->ensureDirectoryExists(app_path("Http/Controllers/Admin/ACL"));
        (new Filesystem)->copyDirectory(__DIR__."/../../../stubs/app/Http/Controllers/Admin/ACL", app_path("Http/Controllers/Admin/ACL"));
        (new Filesystem)->ensureDirectoryExists(app_path("Imports/ACLs"));
        (new Filesystem)->copyDirectory(__DIR__."/../../../stubs/app/Imports/ACLs", app_path("Imports/ACLs"));
        (new Filesystem)->ensureDirectoryExists(app_path("Exports/ACLs"));
        (new Filesystem)->copyDirectory(__DIR__."/../../../stubs/app/Exports/ACLs", app_path("Exports/ACLs"));
        (new Filesystem)->ensureDirectoryExists(app_path("Http/Requests/Admin/ACLs"));
        (new Filesystem)->copyDirectory(__DIR__."/../../../stubs/app/Http/Requests/Admin/ACLs", app_path("Http/Requests/Admin/ACLs"));
        (new Filesystem)->ensureDirectoryExists(app_path("Http/Responses"));

        $this->helper->putTrait($this->helper->classToFile(get_class(app(AuthModelContract::class))), \Tripteki\ACL\Traits\RolePermissionTrait::class);
        $this->helper->putMiddleware(null, "role", \Tripteki\ACL\Http\Middleware\RoleMiddleware::class);
        $this->helper->putMiddleware(null, "permission", \Tripteki\ACL\Http\Middleware\PermissionMiddleware::class);
        $this->helper->putMiddleware(null, "role_or_permission", \Tripteki\ACL\Http\Middleware\RoleOrPermissionMiddleware::class);

        $this->info("Adminer ACL scaffolding installed successfully.");
    }
};
