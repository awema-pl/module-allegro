<?php
namespace AwemaPL\Allegro\Sections\Accounts\Services\Contracts;

use AwemaPL\Allegro\Client\AllegroRestApi;
use AwemaPL\Allegro\Sections\Accounts\Models\Account;

interface Authorization
{
    /**
     * Get client ID
     *
     * @param Account $account
     * @return string
     */
    public function getAuthLink(Account $account);

    /**
     * Get token
     *
     * @param Account $account
     * @param string $code
     * @return mixed
     */
    public function getToken(Account $account, $code);

    /**
     * Get REST API
     * @param Account $account
     * @return AllegroRestApi
     */
    public function getRestApi(Account $account);

    /**
     * Get REST API by access token
     * @param Account $account
     * @param string $accessToken
     * @return AllegroRestApi
     */
    public function getRestApiByAccessToken(Account $account, string $accessToken);
}
