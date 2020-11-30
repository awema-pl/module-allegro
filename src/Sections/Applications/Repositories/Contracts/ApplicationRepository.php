<?php

namespace AwemaPL\Allegro\Sections\Applications\Repositories\Contracts;

use AwemaPL\Allegro\Sections\Applications\Repositories\EloquentApplicationRepository;
use AwemaPL\Allegro\Sections\Options\Http\Requests\UpdateOption;
use Illuminate\Http\Request;

interface ApplicationRepository
{
    /**
     * Create application
     *
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $data);

    /**
     * Scope application
     *
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function scope($request);
    
    /**
     * Update application
     *
     * @param array $data
     * @param int $id
     *
     * @return int
     */
    public function update(array $data, $id);
    
    /**
     * Delete application
     *
     * @param int $id
     */
    public function delete($id);

    /**
     * Find a model by its primary key.
     *
     * @param  mixed  $id
     * @param  array  $columns
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|static[]|static|null
     */
    public function find($id, $columns = ['*']);
}
