<?php

namespace harshbhoraniya19\RolePermission\Middleware;

use Closure;
use harshbhoraniya19\RolePermission\Exceptions\PermissionDeniedException;
use Illuminate\Contracts\Auth\Guard;

class PermissionMiddleware
{
    protected $auth;

    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    public function handle($request, Closure $closure, $permission)
    {
        if($this->auth->check() && $this->auth->user()->hasPermission($permission)){
            return $closure($request);
        }

        throw new PermissionDeniedException($permission);
    }
}