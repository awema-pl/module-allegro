<?php

namespace AwemaPL\Allegro\Sections\Accounts\Http\Controllers;

use AwemaPL\Allegro\Sections\Accounts\Http\Requests\StoreAccount;
use AwemaPL\Allegro\Sections\Accounts\Http\Requests\UpdateAccount;
use AwemaPL\Allegro\Sections\Accounts\Models\Account;
use AwemaPL\Allegro\Sections\Accounts\Repositories\Contracts\AccountRepository;
use AwemaPL\Allegro\Sections\Accounts\Resources\EloquentAccount;
use AwemaPL\Allegro\Sections\Accounts\Services\Contracts\Authorization;
use AwemaPL\Auth\Controllers\Traits\RedirectsTo;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AccountController extends Controller
{

    use RedirectsTo, AuthorizesRequests;

    /**
     * Accounts repository instance
     *
     * @var AccountRepository
     */
    protected $accounts;

    /** @var Authorization */
    protected $authorization;

    public function __construct(AccountRepository $accounts, Authorization $authorization)
    {
        $this->accounts = $accounts;
        $this->authorization = $authorization;
    }

    /**
     * Display accounts
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('allegro::sections.accounts.index');
    }

    /**
     * Request scope
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function scope(Request $request)
    {
        return EloquentAccount::collection(
            $this->accounts->scope($request)
                ->isOwner()
                ->latest()->smartPaginate()
        );
    }

    /**
     * Create account
     *
     * @param StoreAccount $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function store(StoreAccount $request)
    {
        $account = $this->accounts->create($request->all());
        $url = $this->authorization->getAuthLink($account);
        return $this->ajaxRedirectToUrl($url);
    }

    /**
     * Update account
     *
     * @param UpdateAccount $request
     * @param $id
     * @return array
     */
    public function update(UpdateAccount $request, $id)
    {
        $this->authorize('isOwner', Account::find(id));
        $this->accounts->update($request->all(), $id);
        return notify(_p('allegro::notifies.account.success_updated_account', 'Success updated account.'));
    }
    
    /**
     * Delete account
     *
     * @param $id
     * @return array
     */
    public function delete($id)
    {
        $this->authorize('isOwner', Account::find($id));
        $this->accounts->delete($id);
        return notify(_p('allegro::notifies.account.success_deleted_account', 'Success deleted account.'));
    }

    /**
     * Reconnect account
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function reconnect(Request $request, $id)
    {
        $this->authorize('isOwner', Account::find($id));
        $account = $this->accounts->find($id);
        $url = $this->authorization->getAuthLink($account);
        return $this->ajaxRedirectToUrl($url);
    }
}
