<?php

namespace AwemaPL\Allegro;

use AwemaPL\Allegro\Sections\Settings\Models\Setting;
use AwemaPL\Allegro\Sections\Settings\Repositories\Contracts\SettingRepository;
use Illuminate\Database\Migrations\Migrator;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use AwemaPL\Allegro\Contracts\Allegro as AllegroContract;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;

class Allegro implements AllegroContract
{
    /** @var Router $router */
    protected $router;

    /** @var SettingRepository $settings */
    protected $settings;

    public function __construct(Router $router, SettingRepository $settings)
    {
        $this->router = $router;
        $this->settings = $settings;
    }

    /**
     * Routes
     */
    public function routes()
    {
        if ($this->isActiveRoutes()) {
            if ($this->isActiveInstallationRoutes() && (!$this->isMigrated() || !$this->settings->exist())) {
                $this->installationRoutes();
            }
            if ($this->isActiveSettingRoutes()) {
                $this->settingRoutes();
            }
        }
    }

    /**
     * Installation routes
     */
    protected function installationRoutes()
    {
        $prefix = config('allegro.routes.installation.prefix');
        $namePrefix = config('allegro.routes.installation.name_prefix');
        $this->router->prefix($prefix)->name($namePrefix)->group(function () {
            $this->router
                ->get('/', '\AwemaPL\Allegro\Sections\Installations\Http\Controllers\InstallationController@index')
                ->name('index');
            $this->router->post('/', '\AwemaPL\Allegro\Sections\Installations\Http\Controllers\InstallationController@store')
                ->name('store');
        });

    }

    /**
     * Setting routes
     */
    protected function settingRoutes()
    {
        $prefix = config('allegro.routes.setting.prefix');
        $namePrefix = config('allegro.routes.setting.name_prefix');
        $middleware = config('allegro.routes.setting.middleware');
        $this->router->prefix($prefix)->name($namePrefix)->middleware($middleware)->group(function () {
            $this->router
                ->get('/', '\AwemaPL\Allegro\Sections\Settings\Http\Controllers\SettingController@index')
                ->name('index');
            $this->router
                ->get('/applications', '\AwemaPL\Allegro\Sections\Settings\Http\Controllers\SettingController@scope')
                ->name('scope');
            $this->router
                ->patch('{id?}', '\AwemaPL\Allegro\Sections\Settings\Http\Controllers\SettingController@update')
                ->name('update');
        });
    }

    /**
     * Can installation
     *
     * @return bool
     */
    public function canInstallation()
    {
        $canForPermission = $this->canInstallForPermission();
        return $this->isActiveRoutes()
            && $this->isActiveInstallationRoutes()
            && $canForPermission
            && (!$this->isMigrated() ||!$this->settings->exist());
    }

    /**
     * Is migrated
     *
     * @return bool
     */
    public function isMigrated()
    {
        $tablesInDb = array_map('reset', \DB::select('SHOW TABLES'));

        $tables = array_values(config('allegro.database.tables'));
        foreach ($tables as $table){
            if (!in_array($table, $tablesInDb)){
                return false;
            }
        }
        return true;
    }

    /**
     * Is active routes
     *
     * @return \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed
     */
    public function isActiveRoutes()
    {
        return config('allegro.routes.active');
    }

    /**
     * Is active setting routes
     *
     * @return \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed
     */
    public function isActiveSettingRoutes()
    {
        return config('allegro.routes.setting.active');
    }

    /**
     * Is active installation routes
     *
     * @return \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed
     */
    private function isActiveInstallationRoutes()
    {
        return config('allegro.routes.installation.active');
    }

    /**
     * Include lang JS
     */
    public function includeLangJs()
    {
        $lang = config('indigo-layout.frontend.lang', []);
        $lang = array_merge_recursive($lang, app(\Illuminate\Contracts\Translation\Translator::class)->get('allegro::js')?:[]);
        app('config')->set('indigo-layout.frontend.lang', $lang);
    }

    /**
     * Can install for permission
     *
     * @return bool
     */
    private function canInstallForPermission()
    {
        $userClass = config('auth.providers.users.model');
        if (!method_exists($userClass, 'hasRole')) {
            return true;
        }

        if ($user = request()->user() ?? null){
            return $user->can(config('allegro.installation.auto_redirect.permission'));
        }

        return false;
    }

    /**
     * Menu merge in navigation
     */
    public function menuMerge()
    {
        if ($this->canMergeMenu()){
            $allegroMenu = config('allegro-menu.navs', []);
            $navTemp = config('temp_navigation.navs', []);
            $nav = array_merge_recursive($navTemp, $allegroMenu);
            config(['temp_navigation.navs' => $nav]);
        }
    }

    /**
     * Can merge menu
     *
     * @return boolean
     */
    private function canMergeMenu()
    {
        return !!config('allegro-menu.merge_to_navigation') && self::isMigrated();
    }

    /**
     * Execute package migrations
     */
    public function migrate()
    {
         Artisan::call('migrate', ['--force' => true, '--path'=>'vendor/awema-pl/module-allegro/database/migrations']);
    }

    /**
     * Install package
     *
     * @param array $data
     */
    public function install(array $data)
    {
        $this->migrate();
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        Artisan::call('cache:clear');

        $this->settings->create([
            'key' => 'default_client_id',
            'value' =>$data['default_client_id']
        ]);
        $this->settings->create([
            'key' => 'default_client_secret',
            'value' =>$data['default_client_secret']
        ]);
    }

    /**
     * Add permissions for module permission
     */
    public function mergePermissions()
    {
       if ($this->canMergePermissions()){
           $allegroPermissions = config('allegro.permissions');
           $tempPermissions = config('temp_permission.permissions', []);
           $permissions = array_merge_recursive($tempPermissions, $allegroPermissions);
           config(['temp_permission.permissions' => $permissions]);
       }
    }

    /**
     * Can merge permissions
     *
     * @return boolean
     */
    private function canMergePermissions()
    {
        return !!config('allegro.merge_permissions');
    }
}
