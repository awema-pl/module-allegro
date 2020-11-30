<?php

namespace AwemaPL\Allegro\Sections\Callbacks\Http\Controllers;

use AwemaPL\Allegro\Sections\Accounts\Repositories\Contracts\AccountRepository;
use AwemaPL\Allegro\Sections\Accounts\Services\Contracts\Authorization;
use AwemaPL\Auth\Controllers\Traits\RedirectsTo;
use AwemaPL\Allegro\Facades\Allegro;
use AwemaPL\Allegro\Sections\Installations\Http\Requests\StoreInstallation;
use AwemaPL\SystemNotify\Notify;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class CallbackController extends Controller
{
    /** @var AccountRepository */
    protected $accounts;

    /** @var Authorization */
    protected $authorization;

    public function __construct(AccountRepository $accounts, Authorization $authorization)
    {
        $this->accounts = $accounts;
        $this->authorization = $authorization;
    }

    /**
     * Display installation form
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function add(Request $request, $id)
    {
        $account = $this->accounts->find($id);
        $code = $request->code;
        $token = $this->authorization->getToken($account, $code);
        $restApi = $this->authorization->getRestApiByAccessToken($account, $token->access_token);
        $response = $restApi->get('/me');
        if ($account->seller_id && $account->seller_id !==  $response->id){
            notifyMessage(_p('allegro::notifies.account.another_account_cannot_connected', 'Another account cannot be connected.'), ['status' =>'error']);
            return redirect(route('allegro.account.index'));
        }
        if ($this->accounts->existSellerId($response->id)){
            $existAccount = $this->accounts->getBySellerId($response->id);
            if ($existAccount->id !== (int) $id){
                $this->accounts->delete($id);
                notifyMessage(_p('allegro::notifies.account.account_is_already_connected', 'This account is already connected.'), ['status' =>'error']);
                return redirect(route('allegro.account.index'));
            }
        }
        $this->accounts->update([
            'seller_id' => $response->id,
            'username' => $response->login,
            'access_token' => $token->access_token,
            'refresh_token' =>$token->refresh_token,
        ], $id);

        notifyMessage(_p('allegro::notifies.account.success_connected_account', 'Success connected account.'));
        return redirect(route('allegro.account.index'));

    }
}
