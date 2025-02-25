<?php

namespace harshbhoraniya19\RolePermission;

use Illuminate\Support\ServiceProvider;

class RolePermissionServiceProvider extends ServiceProvider
{
    private $_packageTag = 'laravelroles';

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap any application services.
     *
     * @param \Illuminate\Routing\Router $router The router
     *
     * @return void
     */
    public function boot()
    {
        $this->app['router']->aliasMiddleware('role', RoleMiddleware::class);
        $this->app['router']->aliasMiddleware('permission', PermissionMiddleware::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
}