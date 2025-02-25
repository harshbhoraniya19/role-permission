<?php

namespace harshbhoraniya19\RolePermission\Traits;

use App\Models\User;
use harshbhoraniya19\RolePermission\Models\Role;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait PermissionRelations{

    public function roles(){
        return $this->belongsToMany(Role::class);
    }

    public function users(){
        return $this->belongsToMany(User::class);
    }
}