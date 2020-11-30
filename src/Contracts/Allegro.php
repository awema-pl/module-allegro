<?php

namespace AwemaPL\Allegro\Contracts;

use Illuminate\Http\Request;

interface Allegro
{
    /**
     * Register routes.
     *
     * @return void
     */
    public function routes();

    /**
     * Can installation
     *
     * @return mixed
     */
    public function canInstallation();

    /**
     * Include Lang JS
     */
    public function includeLangJs();


    /**
     * Menu merge in navigation
     */
    public function menuMerge();

    /**
     * Install package
     *
     * @param array $data
     */
    public function install(array $data);

    /**
     * Add permissions for module permission
     *
     * @return mixed
     */
    public function mergePermissions();
}
