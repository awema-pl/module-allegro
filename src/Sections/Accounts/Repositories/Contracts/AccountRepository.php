<?php

namespace AwemaPL\Allegro\Sections\Accounts\Repositories\Contracts;

use Illuminate\Http\Request;

interface AccountRepository
{
    /**
     * Create account
     *
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $data);

    /**
     * Scope account
     *
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function scope($request);
    
    /**
     * Update account
     *
     * @param array $data
     * @param int $id
     *
     * @return int
     */
    public function update(array $data, $id);
    
    /**
     * Delete account
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

    /**
     * Exist seller ID
     *
     * @param $sellerId
     * @param null $userId
     * @return mixed
     */
    public function existSellerId($sellerId, $userId = null);

    /**
     * Get by seller ID
     *
     * @param $sellerId
     * @param null $userId
     * @return mixed
     */
    public function getBySellerId($sellerId, $userId = null);
}
