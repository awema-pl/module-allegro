<?php

namespace AwemaPL\Allegro\Sections\Accounts\Models\Contracts;

use Carbon\Carbon;

interface Account
{

    /**
     * Get the user that owns the account.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user();

    /**
     * Get the application
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function application();

    /**
     * Get refresh token expires at
     *
     * @return Carbon|null
     */
    public function getRefreshTokenExpiresAtAttribute();

    /**
     * Is refresh token expired
     *
     * @return bool
     */
    public function getIsRefreshTokenExpiredAtAttribute();

    /**
     * Get access token expires at
     *
     * @return Carbon|null
     */
    public function getAccessTokenExpiresAtAttribute();


    /**
     * Is access token expired
     *
     * @return bool
     */
    public function getIsAccessTokenExpiredAtAttribute();
}
