<h1 align="center">ACL</h1>

This package provides implementation of Access Control List (ACL) Roles-Permissions in repository pattern for Lumen and Laravel besides REST API starterpack of admin management with no intervention to codebase and keep clean.

Getting Started
---

Installation :

```
composer require tripteki/laravelphp-acl
```

How to use it :

- Read detail optional instruction here [Lumen](https://spatie.be/docs/laravel-permission/installation-lumen) or [Laravel](https://spatie.be/docs/laravel-permission/installation-laravel).

- Put to any of your model ruleable.

```php

/**
 * @return array<string, string>
 */
protected $dispatchesEvents = [

    "created" => \Tripteki\ACL\Events\Created::class,
    "deleted" => \Tripteki\ACL\Events\Deleted::class,
    "restored" => \Tripteki\ACL\Events\Created::class,
    "forceDeleted" => \Tripteki\ACL\Events\Deleted::class,
];
```

- Put `Tripteki\ACL\Providers\ACLServiceProvider` to service provider configuration list.

- Put `Tripteki\ACL\Providers\ACLServiceProvider::ignoreConfig()` into `register` provider, then publish config file into your project's directory with running :

```
php artisan vendor:publish --tag=tripteki-laravelphp-acl
```

- Put `Tripteki\ACL\Providers\ACLServiceProvider::ignoreMigrations()` into `register` provider, then publish migrations file into your project's directory with running (optionally) :

```
php artisan vendor:publish --tag=tripteki-laravelphp-acl-migrations
```

- Migrate.

```
php artisan migrate
```

- Emit Event-Listener.

```
php artisan queue:work
```

- Publish tests file into your project's directory with running (optionally) :

```
php artisan vendor:publish --tag=tripteki-laravelphp-acl-tests
```

- Sample :

```php
use Tripteki\ACL\Contracts\Repository\Admin\IACLRoleRepository;
use Tripteki\ACL\Contracts\Repository\Admin\IACLPermissionRepository;
use Tripteki\ACL\Contracts\Repository\IACLRepository;

$roleRepository = app(IACLRoleRepository::class);
$permissionRepository = app(IACLPermissionRepository::class);

/*
 * As `{resource}`.`{action}`.`{target}` is representing :
 *
 * - {resource} : 'posts' = 'posts.*' = 'posts.*.*'
 * - {action} : 'viewAny', 'view', 'create', 'update', 'delete'
 * - {target} : '[identifier]'
 */

// $permissionRepository->rule("posts.update.*"); //
// $permissionRepository->unrule("posts.update.*"); //
// $permissionRepository->get("posts.update.*"); //
// $permissionRepository->all(); //

// $roleRepository->rule("admin"); //
// $roleRepository->rule("user"); //
// $roleRepository->unrule("admin"); //
// $roleRepository->unrule("user"); //
// $roleRepository->get("admin"); //
// $roleRepository->get("user"); //
// $roleRepository->all(); //

// $roleRepository->forRole("admin"); //
// $roleRepository->grant("posts.update.*"); //
// $roleRepository->revoke("posts.update.*"); //
// $roleRepository->ability("posts.update.*"); //
// $roleRepository->permissions(); //

$repository = app(IACLRepository::class);
// $repository->setUser(...); //
// $repository->getUser(); //

// $repository->grantAs("admin"); //
// $repository->revokeAs("admin"); //
// $repository->is("admin"); //
// $repository->permissions(); //
// $repository->grant("posts.update.5"); //
// $repository->revoke("posts.update.5"); //
// $repository->owns(); //
// $repository->can(iacl(\App\Models\Post::class, "update", 5)); //
// $repository->can("posts.update.5"); //
// auth()->user()->can("posts.update.5"); //
// auth()->user()->canAny([ "posts.update.5", ]); //
// auth()->user()->cant("posts.update.5"); //
// auth()->user()->cantAny([ "posts.update.5", ]); //
```

- Generate swagger files into your project's directory with putting this into your annotation configuration (optionally) :

```
base_path("app/Http/Controllers/ACL")
```

```
base_path("app/Http/Controllers/Admin/ACL")
```

Usage
---

`php artisan adminer:install:acl`

Author
---

- Trip Teknologi ([@tripteki](https://linkedin.com/company/tripteki))
- Hasby Maulana ([@hsbmaulana](https://linkedin.com/in/hsbmaulana))
