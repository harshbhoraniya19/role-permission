<?php

namespace harshbhoraniya19\RolePermission\Traits;

use App\Models\User;
use harshbhoraniya19\RolePermission\Models\Permission;
use harshbhoraniya19\RolePermission\Models\Role;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait RoleAndPermission{

    public function getRole($id)
    {
        return Role::findOrFail($id);
    }

    public function getPermission($id)
    {
        return Permission::findOrFail($id);
    }

    public function deletePermission($id)
    {
        $permission = $this->getPermission($id);
        $permission->delete();

        return $permission;
    }

    public function destroyPermission($id)
    {
        $permission = $this->getDeletedPermission($id);
        $this->removeUsersAndRolesFromPermissions($permission);
        $permission->forceDelete();

        return $permission;
    }

    public function deleteRole($id)
    {
        $role = $this->getRole($id);
        $role->delete();

        return $role;
    }
}