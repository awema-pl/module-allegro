<?php

namespace AwemaPL\Allegro\Sections\Accounts\Services;

use AwemaPL\Allegro\Client\AllegroRestApi;
use AwemaPL\Allegro\Sections\Accounts\Models\Account;
use AwemaPL\Allegro\Sections\Accounts\Repositories\Contracts\AccountRepository;
use AwemaPL\Allegro\Sections\Settings\Repositories\Contracts\SettingRepository;
use AwemaPL\Allegro\Client\AllegroApiException;
use AwemaPL\Allegro\Sections\Accounts\Services\Contracts\Authorization as AuthorizationContract;
use Carbon\Carbon;
use Exception;
use DateTimeImmutable;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Token;
use Lcobucci\JWT\Token\RegisteredClaims;

class Authorization implements AuthorizationContract
{

    /** @var SettingRepository */
    protected $settings;

    public function __construct(SettingRepository $settings)
    {
        $this->settings = $settings;
    }

    /**
     * Get client ID
     *
     * @param Account $account
     * @return string
     */
    public function getAuthLink(Account $account)
    {
        $application = $account->application;
       $clientId = $application->client_id ?? $this->settings->getValue('default_client_id');
        $sandbox = $application->sandbox ?? false;
        return AllegroRestApi::getAuthLink($clientId, route('allegro.callback.add', ['id' =>$account->id]), $sandbox);
    }

    /**
     * Get token
     *
     * @param Account $account
     * @param string $code
     * @return mixed
     */
    public function getToken(Account $account, $code)
    {
        $application = $account->application;
        $clientId = $application->client_id ?? $this->settings->getValue('default_client_id');
        $clientSecret = $application->client_secret ?? $this->settings->getValue('default_client_secret');
        $sandbox = $application->sandbox ?? false;
        return AllegroRestApi::generateToken($code, $clientId,$clientSecret, route('allegro.callback.add', ['id' =>$account->id]), $sandbox);
    }

    /**
     * Get REST API
     * @param Account $account
     * @return AllegroRestApi
     */
    public function getRestApi(Account $account)
    {
        $application = $account->application;
        $sandbox = $application->sandbox ?? false;
        $accessToken  =$this->getAccessToken($account);
        return new AllegroRestApi($accessToken, $sandbox);
    }

    /**
     * Get REST API by access token
     * @param Account $account
     * @param string $accessToken
     * @return AllegroRestApi
     */
    public function getRestApiByAccessToken(Account $account, string $accessToken)
    {
        $application = $account->application;
        $sandbox = $application->sandbox ?? false;
        return new AllegroRestApi($accessToken, $sandbox);
    }

    /**
     * Get access token
     *
     * @param $account
     * @return |null
     * @throws AllegroApiException
     */
    private function getAccessToken($account)
    {
        $accessToken = $account->access_token;
        if (!$accessToken){
            return null;
        }
        $refreshToken = $account->refresh_token;
        if (!$refreshToken){
            return null;
        }
        if ($account->isRefreshTokenExpiredAt){
            throw new AllegroApiException(
                'Allegro API error. Refresh token is expired.',AllegroApiException::ERROR_API_ALLEGRO, 403, null, _p('allegro::exceptions.refresh_token_expired', 'Allegro refresh token is expired.')
            );
        }
        $application = $account->application;
        $clientId = $application->client_id ?? $this->settings->getValue('default_client_id');
        $clientSecret = $application->client_secret ?? $this->settings->getValue('default_client_secret');
        $sandbox = $application->sandbox ?? false;
        $token =  AllegroRestApi::refreshToken($refreshToken, $clientId,$clientSecret, route('allegro.callback.add', ['id' =>$account->id]), $sandbox);
        $account->update([
            'access_token' => $token->access_token,
            'refresh_token' =>$token->refresh_token,
        ]);
        return $token->access_token;
    }

}
