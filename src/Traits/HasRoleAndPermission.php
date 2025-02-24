<?php

namespace harsh\LaravelRolePermission\Traits;

use App\Models\User;
use harsh\LaravelRolePermission\Models\Permission;
use harsh\LaravelRolePermission\Models\Role;
use Illuminate\Database\Eloquent\Model;
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
}