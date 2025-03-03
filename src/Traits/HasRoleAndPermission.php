<?php

namespace harshbhoraniya19\RolePermission\Traits;

use App\Models\User;
use harshbhoraniya19\RolePermission\Models\Permission;
use harshbhoraniya19\RolePermission\Models\Role;
use harshbhoraniya19\RolePermission\Models\RoleUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;
use InvalidArgumentException;

trait HasRoleAndPermission{

    protected $permissions;

    protected $roles;

    public function hasPermission($permission, $all=false){
        if (!$all) {
            return $this->hasOnePermission($permission);
        }

        return $this->hasAllPermissions($permission);
    }

    public function hasOnePermission($permission)
    {
        foreach ($this->getArrayFrom($permission) as $permission) {
            if ($this->checkPermission($permission)) {
                return true;
            }
        }

        return false;
    }

    public function hasAllPermissions($permission)
    {
        foreach ($this->getArrayFrom($permission) as $permission) {
            if (!$this->checkPermission($permission)) {
                return false;
            }
        }

        return true;
    }

    public function checkPermission($permission)
    {
        return $this->getPermissions()->contains(function ($value) use ($permission) {
            return $permission == $value->id || Str::is($permission, $value->slug);
        });
    }

    private function getArrayFrom($argument)
    {
        return (!is_array($argument)) ? preg_split('/ ?[,|] ?/', $argument) : $argument;
    }

    public function getPermissions()
    {
        return (!$this->permissions) ? $this->permissions = $this->rolePermissions()->get()->merge($this->userPermissions()->get()) : $this->permissions;
    }
    public function getRoles()
    {
        return (!$this->roles) ? $this->roles = $this->rolePermissions()->get()->merge($this->userPermissions()->get()) : $this->roles;
    }

    public function rolePermissions(){

        $permissionTable = 'permission';
        $roleTable = 'role';

        if (!Permission::class instanceof Model) {
            throw new InvalidArgumentException('Permission must be an instance of \Illuminate\Database\Eloquent\Model');
        }

        return Permission::class::select([$permissionTable.'.*', 'permission_role.created_at as pivot_created_at', 'permission_role.updated_at as pivot_updated_at'])
            ->join('permission_role', 'permission_role.permission_id', '=', $permissionTable.'.id')
            ->join($roleTable, $roleTable.'.id', '=', 'permission_role.role_id')
            ->whereIn($roleTable.'.id', $this->getRoles()->pluck('id')->toArray())
            ->orWhere($roleTable.'.level', '<', $this->level())
            ->groupBy([$permissionTable.'.id', $permissionTable.'.name', $permissionTable.'.slug', $permissionTable.'.description', $permissionTable.'.model', $permissionTable.'.created_at', 'permissions.updated_at', $permissionTable.'.deleted_at', 'pivot_created_at', 'pivot_updated_at']);
    }

    public function userPermissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_user');
    }

    public function hasRole($role, $all = false)
    {
        if (!$all) {
            return $this->hasOneRole($role);
        }

        return $this->hasAllRoles($role);
    }

    public function hasOneRole($role)
    {
        foreach ($this->getArrayFrom($role) as $role) {
            if ($this->checkRole($role)) {
                return true;
            }
        }

        return false;
    }

    public function hasAllRoles($role)
    {
        foreach ($this->getArrayFrom($role) as $role) {
            if (!$this->checkRole($role)) {
                return false;
            }
        }

        return true;
    }

    public function checkRole($role)
    {
        return $this->getRoles()->contains(function ($value) use ($role) {
            return $role == $value->id || Str::is($role, $value->slug);
        });
    }

    public function attachRole($role)
    {
        if ($this->getRoles()->contains($role)) {
            return true;
        }
        $this->resetRoles();

        return $this->roles()->attach($role);
    }

    public function detachRole($role)
    {
        $this->resetRoles();

        return $this->roles()->detach($role);
    }

    public function detachAllRoles()
    {
        $this->resetRoles();

        return $this->roles()->detach();
    }

    public function syncRoles($roles)
    {
        $this->resetRoles();

        return $this->roles()->sync($roles);
    }

    protected function resetRoles()
    {
        $this->roles = null;
        if (method_exists($this, 'unsetRelation')) {
            $this->unsetRelation('roles');
        } else {
            unset($this->relations['roles']);
        }
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, RoleUser::class)->withTimestamps();
    }

    public function attachPermission($permission)
    {
        if ($this->getPermissions()->contains($permission)) {
            return true;
        }
        $this->permissions = null;

        return $this->userPermissions()->attach($permission);
    }

    public function detachPermission($permission)
    {
        $this->permissions = null;

        return $this->userPermissions()->detach($permission);
    }

    public function detachAllPermissions()
    {
        $this->permissions = null;

        return $this->userPermissions()->detach();
    }

    public function syncPermissions($permissions)
    {
        $this->permissions = null;

        return $this->userPermissions()->sync($permissions);
    }

    public function callMagic($method, $parameters)
    {
        if (starts_with($method, 'is')) {
            return $this->hasRole(snake_case(substr($method, 2), config('roles.separator')));
        } elseif (starts_with($method, 'can')) {
            return $this->hasPermission(snake_case(substr($method, 3), config('roles.separator')));
        }

        return parent::__call($method, $parameters);
    }

    public function __call($method, $parameters)
    {
        return $this->callMagic($method, $parameters);
    }
}