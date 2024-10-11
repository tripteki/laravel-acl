<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\PermissionRegistrar;
use Tripteki\Helpers\Contracts\AuthModelContract;
use Tripteki\ACL\Providers\ACLServiceProvider;
use Tripteki\ACL\Contracts\Repository\Admin\IACLRoleRepository;
use Tripteki\ACL\Contracts\Repository\Admin\IACLPermissionRepository;

class CreateRolesPermissionsTable extends Migration
{
    /**
     * @var string
     */
    protected $keytype;

    /**
     * @var \Tripteki\ACL\Contracts\Repository\Admin\IACLRoleRepository
     */
    protected $role;

    /**
     * @var \Tripteki\ACL\Contracts\Repository\Admin\IACLPermissionRepository
     */
    protected $permission;

    /**
     * @var \Spatie\Permission\PermissionRegistrar
     */
    protected $registrar;

    /**
     * @return void
     */
    public function __construct()
    {
        $this->keytype = app(AuthModelContract::class)->getKeyType();
        $this->role = app(IACLRoleRepository::class);
        $this->permission = app(IACLPermissionRepository::class);
        $this->registrar = app(PermissionRegistrar::class);
    }

    /**
     * @return void
     */
    public function up()
    {
        $keytype = $this->keytype;
        $tableNames = config("permission.table_names");
        $columnNames = config("permission.column_names");
        $teams = config("permission.teams");

        if (empty($tableNames)) {

            throw new \Exception("Error: config/permission.php not loaded. Run [php artisan config:clear] and try again.");
        }

        if ($teams && empty($columnNames["team_foreign_key"] ?? null)) {

            throw new \Exception("Error: team_foreign_key on config/permission.php not loaded. Run [php artisan config:clear] and try again.");
        }

        Schema::create($tableNames["permissions"], function (Blueprint $table) {

            $table->uuid("id");
            $table->string("name");
            $table->string("guard_name");
            $table->timestamps();
            $table->unique([ "name", "guard_name", ]);

            $table->primary("id");
        });

        Schema::create($tableNames["roles"], function (Blueprint $table) use ($teams, $columnNames) {

            $table->uuid("id");

            if ($teams || config("permission.testing")) {

                $table->unsignedBigInteger($columnNames["team_foreign_key"])->nullable();
                $table->index($columnNames["team_foreign_key"], "roles_team_foreign_key_index");
            }

            $table->string("name");
            $table->string("guard_name");
            $table->timestamps();

            if ($teams || config("permission.testing")) {

                $table->unique([ $columnNames["team_foreign_key"], "name", "guard_name", ]);

            } else {

                $table->unique([ "name", "guard_name", ]);
            }

            $table->primary("id");
        });

        Schema::create($tableNames["model_has_permissions"], function (Blueprint $table) use ($keytype, $tableNames, $columnNames, $teams) {

            $table->string("model_type");

            if ($keytype == "int") $table->unsignedBigInteger($columnNames["model_morph_key"]);
            else if ($keytype == "string") $table->uuid($columnNames["model_morph_key"]);

            $table->index([ $columnNames["model_morph_key"], "model_type", ], "model_has_permissions_model_id_model_type_index");
            $table->foreignUuid($this->registrar->pivotPermission)->references("id")->on($tableNames["permissions"])->onUpdate("cascade")->onDelete("cascade");

            if ($teams) {

                $table->unsignedBigInteger($columnNames["team_foreign_key"]);
                $table->index($columnNames["team_foreign_key"], "model_has_permissions_team_foreign_key_index");
                $table->primary([ $columnNames["team_foreign_key"], $this->registrar->pivotPermission, $columnNames["model_morph_key"], "model_type", ], "model_has_permissions_permission_model_type_primary");

            } else {

                $table->primary([ $this->registrar->pivotPermission, $columnNames["model_morph_key"], "model_type", ], "model_has_permissions_permission_model_type_primary");
            }
        });

        Schema::create($tableNames["model_has_roles"], function (Blueprint $table) use ($keytype, $tableNames, $columnNames, $teams) {

            $table->string("model_type");

            if ($keytype == "int") $table->unsignedBigInteger($columnNames["model_morph_key"]);
            else if ($keytype == "string") $table->uuid($columnNames["model_morph_key"]);

            $table->index([ $columnNames["model_morph_key"], "model_type", ], "model_has_roles_model_id_model_type_index");
            $table->foreignUuid($this->registrar->pivotRole)->references("id")->on($tableNames["roles"])->onUpdate("cascade")->onDelete("cascade");

            if ($teams) {

                $table->unsignedBigInteger($columnNames["team_foreign_key"]);
                $table->index($columnNames["team_foreign_key"], "model_has_roles_team_foreign_key_index");
                $table->primary([ $columnNames["team_foreign_key"], $this->registrar->pivotRole, $columnNames["model_morph_key"], "model_type", ], "model_has_roles_role_model_type_primary");

            } else {

                $table->primary([ $this->registrar->pivotRole, $columnNames["model_morph_key"], "model_type", ], "model_has_roles_role_model_type_primary");
            }
        });

        Schema::create($tableNames["role_has_permissions"], function (Blueprint $table) use ($keytype, $tableNames) {

            $table->foreignUuid($this->registrar->pivotPermission, "acl_role_has_permissions_permission_id_foreign")->references("id")->on($tableNames["permissions"])->onUpdate("cascade")->onDelete("cascade");
            $table->foreignUuid($this->registrar->pivotRole, "acl_role_has_permissions_role_id_foreign")->references("id")->on($tableNames["roles"])->onUpdate("cascade")->onDelete("cascade");

            $table->primary([ $this->registrar->pivotPermission, $this->registrar->pivotRole, ], "role_has_permissions_permission_id_role_id_primary");
        });

        app("cache")->store(config("permission.cache.store") != "default" ? config("permission.cache.store") : null)->forget(config("permission.cache.key"));

        $this->role->rule(ACLServiceProvider::$SUPERADMIN);
    }

    /**
     * @return void
     */
    public function down()
    {
        $tableNames = config("permission.table_names");

        if (empty($tableNames)) {

            throw new \Exception("Error: config/permission.php not found and defaults could not be merged. Please publish the package configuration before proceeding, or drop the tables manually.");
        }

        Schema::drop($tableNames["role_has_permissions"]);
        Schema::drop($tableNames["model_has_roles"]);
        Schema::drop($tableNames["model_has_permissions"]);
        Schema::drop($tableNames["roles"]);
        Schema::drop($tableNames["permissions"]);
    }
};
