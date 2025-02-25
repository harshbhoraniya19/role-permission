<?php

namespace harshbhoraniya19\RolePermission\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

interface HasRoleAndPermission
{
    public function hasPermission($permission, $all = false);

    public function hasOnePermission($permission);

    public function hasAllPermissions($permission);

    public function checkPermission($permission);

    public function getPermissions();

    public function getRoles();

    public function rolePermissions();

    public function userPermissions();
}