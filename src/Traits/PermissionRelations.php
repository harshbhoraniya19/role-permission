<?php

namespace harsh\LaravelRolePermission\Traits;

use App\Models\User;
use harsh\LaravelRolePermission\Models\Role;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait PermissionRelations{

    public function roles(){
        return $this->belongsToMany(Role::class);
    }

    public function users(){
        return $this->belongsToMany(User::class);
    }
}