<?php

namespace harshbhoraniya19\RolePermission\Exceptions;

use Exception;

class RolesDeniedException extends Exception
{
    /**
     * Create a new role denied exception instance.
     *
     * @param string $role
     */
    public function __construct($role)
    {
        $this->message = sprintf("You don't have a required ['%s'] role.", $role);
    }
}