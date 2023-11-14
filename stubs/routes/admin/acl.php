<?php

use App\Http\Controllers\Admin\ACL\UserAdminController;
use App\Http\Controllers\Admin\ACL\RoleAdminController;
use App\Http\Controllers\Admin\ACL\PermissionAdminController;
use App\Http\Controllers\Admin\ACL\ACLAdminController;
use Illuminate\Support\Facades\Route;

Route::prefix(config("adminer.route.admin"))->middleware(config("adminer.middleware.admin"))->group(function () {

    /**
     * ACLs.
     */
    Route::prefix("acls")->group(function () {

        Route::apiResource("users", UserAdminController::class)->only("show")->parameters([ "users" => "user", ]);

        Route::apiResource("roles", RoleAdminController::class)->except("update")->parameters([ "roles" => "role", ]);
        Route::post("roles-import", [ RoleAdminController::class, "import", ]);
        Route::get("roles-export", [ RoleAdminController::class, "export", ]);

        Route::apiResource("permissions", PermissionAdminController::class)->except("update")->parameters([ "permissions" => "permission", ]);
        Route::post("permissions-import", [ PermissionAdminController::class, "import", ]);
        Route::get("permissions-export", [ PermissionAdminController::class, "export", ]);

        Route::put("/{context}/{object}", [ ACLAdminController::class, "rule", ]);
    });
});
