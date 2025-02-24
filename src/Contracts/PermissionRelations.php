<?php

namespace harsh\LaravelRolePermission\Contracts;

interface PermissionRelations{

    public function roles();

    public function users();
}