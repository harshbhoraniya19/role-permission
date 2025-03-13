<?php

namespace harshbhoraniya19\RolePermission\Models;

use harshbhoraniya19\RolePermission\Contracts\RoleRelations as ContractsRoleRelations;
use harshbhoraniya19\RolePermission\Database\Database;
use harshbhoraniya19\RolePermission\Traits\RoleRelations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Database implements ContractsRoleRelations
{
    use RoleRelations;
    use SoftDeletes;

    protected $table = 'roles';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'level',
    ];
}