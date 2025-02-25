<?php

namespace harsh\RolePermission\Traits;

use App\Models\User;
use harsh\RolePermission\Models\Permission;

trait RoleRelations
{

    public function permissions(){
        return $this->belongsToMany(Permission::class);
    }

    public function users(){
        return $this->belongsToMany(User::class);
    }

    public function attachPermission($permissions){
        return (!$this->permissions()->get()->contains($permissions)) ? $this->permissions()->attach($permissions) : true;
    }

    public function detachPermission($permissions){
        return ($this->permissions()->get()->contains($permissions)) ? $this->permissions()->detach($permissions) : true;
    }

    public function detachAllPermission(){
        return $this->permissions()->detach();
    }

    public function syncPermissions($permissions)
    {
        return $this->permissions()->sync($permissions);
    }
}