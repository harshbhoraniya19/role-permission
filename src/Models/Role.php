<?php

namespace harshbhoraniya19\RolePermission\Models;

use harshbhoraniya19\RolePermission\Traits\RoleRelations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
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