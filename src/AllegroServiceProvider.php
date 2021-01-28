<?php

namespace AwemaPL\Allegro;

use AwemaPL\Allegro\Sections\Applications\Models\Application;
use AwemaPL\Allegro\Sections\Applications\Repositories\Contracts\ApplicationRepository;
use AwemaPL\Allegro\Sections\Applications\Repositories\EloquentApplicationRepository;
use AwemaPL\Allegro\Sections\Settings\Repositories\Contracts\SettingRepository;
use AwemaPL\Allegro\Sections\Settings\Repositories\EloquentSettingRepository;
use AwemaPL\Allegro\Sections\Applications\Policies\ApplicationPolicy;
use AwemaPL\BaseJS\AwemaProvider;
use AwemaPL\Allegro\Listeners\EventSubscriber;
use AwemaPL\Allegro\Sections\Installations\Http\Middleware\GlobalMiddleware;
use AwemaPL\Allegro\Sections\Installations\Http\Middleware\GroupMiddleware;
use AwemaPL\Allegro\Sections\Installations\Http\Middleware\Installation;
use AwemaPL\Allegro\Sections\Installations\Http\Middleware\RouteMiddleware;
use AwemaPL\Allegro\Contracts\Allegro as AllegroContract;
use Illuminate\Support\Facades\Event;

class AllegroServiceProvider extends AwemaProvider
{

    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [];

    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'allegro');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'allegro');
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->bootMiddleware();
        app('allegro')->includeLangJs();
        app('allegro')->menuMerge();
        app('allegro')->mergePermissions();
        $this->registerPolicies();
        Event::subscribe(EventSubscriber::class);
        parent::boot();
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/allegro.php', 'allegro');
        $this->mergeConfigFrom(__DIR__ . '/../config/allegro-menu.php', 'allegro-menu');
        $this->app->bind(AllegroContract::class, Allegro::class);
        $this->app->singleton('allegro', AllegroContract::class);
        $this->registerRepositories();
        $this->registerServices();
        parent::register();
    }


    public function getPackageName(): string
    {
        return 'allegro';
    }

    public function getPath(): string
    {
        return __DIR__;
    }

    /**
     * Register and bind package repositories
     *
     * @return void
     */
    protected function registerRepositories()
    {
        $this->app->bind(SettingRepository::class, EloquentSettingRepository::class);
    }

    /**
     * Register and bind package services
     *
     * @return void
     */
    protected function registerServices()
    {

    }


    /**
     * Boot middleware
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    private function bootMiddleware()
    {
        $this->bootGlobalMiddleware();
        $this->bootRouteMiddleware();
        $this->bootGroupMiddleware();
    }

    /**
     * Boot route middleware
     */
    private function bootRouteMiddleware()
    {
        $router = app('router');
        $router->aliasMiddleware('allegro', RouteMiddleware::class);
    }

    /**
     * Boot group middleware
     */
    private function bootGroupMiddleware()
    {
        $kernel = $this->app->make(\Illuminate\Contracts\Http\Kernel::class);
        $kernel->appendMiddlewareToGroup('web', GroupMiddleware::class);
        $kernel->appendMiddlewareToGroup('web', Installation::class);
    }

    /**
     * Boot global middleware
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    private function bootGlobalMiddleware()
    {
        $kernel = $this->app->make(\Illuminate\Contracts\Http\Kernel::class);
        $kernel->pushMiddleware(GlobalMiddleware::class);
    }
}
