<?php

namespace harshbhoraniya19\RolePermission;

use harshbhoraniya19\RolePermission\Middleware\PermissionMiddleware;
use harshbhoraniya19\RolePermission\Middleware\RoleMiddleware;
use harshbhoraniya19\RolePermission\Seeders\DefaultConnectRelationshipsSeeder;
use harshbhoraniya19\RolePermission\Seeders\DefaultPermissionsTableSeeder;
use harshbhoraniya19\RolePermission\Seeders\DefaultRolesTableSeeder;
use harshbhoraniya19\RolePermission\Seeders\DefaultUsersTableSeeder;
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

        $this->registerBladeExtensions();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->loadMigrations();

        $this->publishFiles();
        $this->loadSeedsFrom();
    }

    private function loadMigrations()
    {
        $this->loadMigrationsFrom(__DIR__.'/Database/Migrations');
    }

    /**
     * Loads a seeds.
     *
     * @return void
     */
    private function loadSeedsFrom()
    {
        $this->app->afterResolving('seed.handler', function ($handler) {
            $handler->register(
                DefaultPermissionsTableSeeder::class
            );

            $handler->register(
                DefaultRolesTableSeeder::class
            );

            $handler->register(
                DefaultConnectRelationshipsSeeder::class
            );

            $handler->register(
                DefaultUsersTableSeeder::class
            );
        });
    }

    /**
     * Publish files for package.
     *
     * @return void
     */
    private function publishFiles()
    {
        $publishTag = $this->_packageTag;

        $this->publishes([
            __DIR__.'/Database/Migrations' => database_path('migrations'),
        ], $publishTag.'-migrations');

        $this->publishes([
            __DIR__.'/Database/Seeders' => database_path('seeders'),
        ], $publishTag.'-seeds');

        $this->publishes([
            __DIR__.'/Database/Migrations'      => database_path('migrations'),
            __DIR__.'/Database/Seeders' => database_path('seeders'),
        ], $publishTag);
    }

    /**
     * Register Blade extensions.
     *
     * @return void
     */
    protected function registerBladeExtensions()
    {
        $blade = $this->app['view']->getEngineResolver()->resolve('blade')->getCompiler();

        $blade->directive('role', function ($expression) {
            return "<?php if (Auth::check() && Auth::user()->hasRole({$expression})): ?>";
        });

        $blade->directive('endrole', function () {
            return '<?php endif; ?>';
        });

        $blade->directive('permission', function ($expression) {
            return "<?php if (Auth::check() && Auth::user()->hasPermission({$expression})): ?>";
        });

        $blade->directive('endpermission', function () {
            return '<?php endif; ?>';
        });

        $blade->directive('level', function ($expression) {
            $level = trim($expression, '()');

            return "<?php if (Auth::check() && Auth::user()->level() >= {$level}): ?>";
        });

        $blade->directive('endlevel', function () {
            return '<?php endif; ?>';
        });

        $blade->directive('allowed', function ($expression) {
            return "<?php if (Auth::check() && Auth::user()->allowed({$expression})): ?>";
        });

        $blade->directive('endallowed', function () {
            return '<?php endif; ?>';
        });
    }
}