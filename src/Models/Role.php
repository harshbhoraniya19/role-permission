<?php

namespace harsh\RolePermission\Models;

use harsh\RolePermission\Traits\RoleRelations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use RoleRelations;
    use SoftDeletes;

    protected $table = 'role';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'level',
    ];
}