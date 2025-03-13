<?php

namespace harshbhoraniya19\RolePermission\Models;

use harshbhoraniya19\RolePermission\Contracts\PermissionRelations as ContractsPermissionRelations;
use harshbhoraniya19\RolePermission\Database\Database;
use harshbhoraniya19\RolePermission\Traits\PermissionRelations;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends Database implements ContractsPermissionRelations
{
    use PermissionRelations;
    use SoftDeletes;

    protected $table = 'permissions';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'model',
    ];
}