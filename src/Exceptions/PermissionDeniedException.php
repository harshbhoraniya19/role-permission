<?php

namespace harsh\LaravelRolePermission\Exceptions;

use Exception;

class PermissionDeniedException extends Exception
{
    /**
     * Create a new role denied exception instance.
     *
     * @param string $permission
     */
    public function __construct($permission)
    {
        $this->message = sprintf("You don't have a required ['%s'] permission.", $permission);
    }
}