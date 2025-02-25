<?php

namespace harsh\RolePermission\Models;

use harsh\RolePermission\Traits\PermissionRelations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends Model
{
    use PermissionRelations;
    use SoftDeletes;

    protected $table = 'permission';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'model',
    ];
}