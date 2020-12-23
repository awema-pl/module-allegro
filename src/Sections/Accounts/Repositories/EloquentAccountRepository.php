<?php

namespace AwemaPL\Allegro\Sections\Accounts\Repositories;

use AwemaPL\Allegro\Sections\Accounts\Models\Account;
use AwemaPL\Allegro\Sections\Accounts\Repositories\Contracts\AccountRepository;
use AwemaPL\Allegro\Sections\Accounts\Scopes\EloquentAccountScopes;
use AwemaPL\Repository\Eloquent\BaseRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EloquentAccountRepository extends BaseRepository implements AccountRepository
{
    protected $searchable = [

    ];

    public function entity()
    {
        return Account::class;
    }

    public function scope($request)
    {
        // apply build-in scopes
        parent::scope($request);

        // apply custom scopes
        $this->entity = (new EloquentAccountScopes($request))->scope($this->entity);

        return $this;
    }

    /**
     * Create new role
     *
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $data)
    {
        $data['user_id'] = $data['user_id'] ?? Auth::id();
        return Account::create($data);
    }

    /**
     * Update account
     *
     * @param array $data
     * @param int $id
     * @param string $attribute
     *
     * @return int
     */
    public function update(array $data, $id, $attribute = 'id')
    {
        return parent::update($data, $id, $attribute);
    }

    /**
     * Delete account
     *
     * @param int $id
     */
    public function delete($id){
        $this->destroy($id);
    }

    /**
     * Find a model by its primary key.
     *
     * @param  mixed  $id
     * @param  array  $columns
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|static[]|static|null
     */
    public function find($id, $columns = ['*']){
        return parent::find($id, $columns);
    }

    /**
     * Exist seller ID
     *
     * @param $sellerId
     * @param null $userId
     * @return mixed
     */
    public function existSellerId($sellerId, $userId = null)
    {
        $userId = $userId ?? Auth::id();
        return Account::where('user_id', $userId)->where('seller_id', $sellerId)->exists();
    }

    /**
     * Get by seller ID
     *
     * @param $sellerId
     * @param null $userId
     * @return mixed
     */
    public function getBySellerId($sellerId, $userId = null)
    {
        $userId = $userId ?? Auth::id();
        return Account::where('user_id', $userId)->where('seller_id', $sellerId)->first();
    }

    /**
     * Get by username
     *
     * @param $username
     * @param null $userId
     * @return mixed
     */
    public function getByUsername($username, $userId = null)
    {
        $userId = $userId ?? Auth::id();
        return Account::where('user_id', $userId)->where('username', $username)->first();
    }
}
