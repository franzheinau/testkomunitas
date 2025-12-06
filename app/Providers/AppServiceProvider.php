<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use App\Models\Post;
use App\Policies\PostPolicy;

class AppServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
       /** @var \Illuminate\Routing\Router $router */
    $router = $this->app->make(Router::class);


    $router->aliasMiddleware('role', \Spatie\Permission\Middleware\RoleMiddleware::class);
    $router->aliasMiddleware('permission', \Spatie\Permission\Middleware\PermissionMiddleware::class);
    $router->aliasMiddleware('role_or_permission', \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class);

        if ($this->app->environment('production')) {
        \URL::forceScheme('https');
    }
    }

    protected $policies = [
    Post::class => PostPolicy::class,
];
}
