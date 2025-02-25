<?php

namespace harshbhoraniya19\RolePermission\Contracts;

interface RoleRelations{

    public function permissions();

    public function users();

    public function attachPermission($permissions);

    public function detachPermission($permissions);

    public function detachAllPermission();

    public function syncPermissions($permissions);
}