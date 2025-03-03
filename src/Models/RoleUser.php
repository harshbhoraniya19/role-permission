<?php

namespace harshbhoraniya19\RolePermission\Models;

use harshbhoraniya19\RolePermission\Traits\RoleRelations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoleUser extends Model
{
    use SoftDeletes;

    protected $table = 'roles_user';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'level',
    ];
}