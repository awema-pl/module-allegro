<?php

namespace AwemaPL\Allegro\Admin\Sections\Settings\Repositories\Contracts;

use AwemaPL\Allegro\Admin\Sections\Options\Http\Requests\UpdateOption;
use Illuminate\Http\Request;

interface SettingRepository
{
    /**
     * Create setting
     *
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $data);

    /**
     * Scope setting
     *
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function scope($request);
    
    /**
     * Update setting
     *
     * @param array $data
     * @param int $id
     *
     * @return int
     */
    public function update(array $data, $id);

    /**
     * exist setting
     *
     * @return boolean
     */
    public function exist();

    /**
     * Get value
     *
     * @param $key
     * @return mixed
     */
    public function getValue($key);
}
