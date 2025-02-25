<?php

namespace harsh\RolePermission\Contracts;

interface PermissionRelations{

    public function roles();

    public function users();
}