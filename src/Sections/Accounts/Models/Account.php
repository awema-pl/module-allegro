<?php

namespace AwemaPL\Allegro\Sections\Accounts\Models;

use AwemaPL\Allegro\Sections\Options\Models\Option;
use AwemaPL\Allegro\Sections\Applications\Models\Application;
use betterapp\LaravelDbEncrypter\Traits\EncryptableDbAttribute;
use Carbon\Carbon;
use DateTimeImmutable;
use Illuminate\Database\Eloquent\Model;
use AwemaPL\Allegro\Sections\Accounts\Models\Contracts\Account as AccountContract;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Token;

class Account extends Model implements AccountContract
{

    use EncryptableDbAttribute;

    /** @var array The attributes that should be encrypted/decrypted */
    protected $encryptable = [
        'access_token',
        'refresh_token',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'user_id', 'application_id','username', 'seller_id', 'access_token', 'refresh_token',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'user_id' => 'integer',
        'sandbox' => 'boolean'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [];

    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    public function getTable()
    {
        return config('allegro.database.tables.allegro_accounts');
    }

    /**
     * Get the user that owns the account.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(){
        return $this->belongsTo(config('auth.providers.users.model'));
    }

    /**
     * Get the application
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function application(){
        return $this->belongsTo(Application::class);
    }

    /**
     * Get refresh token expires at
     *
     * @return Carbon|null
     */
    public function getRefreshTokenExpiresAtAttribute()
    {
        $refreshToken = $this->refresh_token;
        if (!$refreshToken){
            return null;
        }
        $parser = new Parser();
        /** @var Token $token */
        $token = $parser->parse($refreshToken);
        /** @var DateTimeImmutable $expiresAt */
        $expiresAt = $token->claims()->get('exp');
        $expiresAt = Carbon::parse($expiresAt,'UTC');
        $expiresAt->setTimezone(config('app.timezone'));
        return $expiresAt;
    }

    /**
     * Is refresh token expired
     *
     * @return bool
     */
    public function getIsRefreshTokenExpiredAtAttribute()
    {
        $expiresAt = $this->refreshTokenExpiresAt;
        if (!$expiresAt){
            return true;
        }
        return now() >= $expiresAt;
    }

    /**
     * Get access token expires at
     *
     * @return Carbon|null
     */
    public function getAccessTokenExpiresAtAttribute()
    {
        $accessToken = $this->access_token;
        if (!$accessToken){
            return null;
        }
        $parser = new Parser();
        /** @var Token $token */
        $token = $parser->parse($accessToken);
        /** @var DateTimeImmutable $expiresAt */
        $expiresAt = $token->claims()->get('exp');
        $expiresAt = Carbon::parse($expiresAt,'UTC');
        $expiresAt->setTimezone(config('app.timezone'));
        return $expiresAt;
    }


    /**
     * Is access token expired
     *
     * @return bool
     */
    public function getIsAccessTokenExpiredAtAttribute()
    {
        $expiresAt = $this->accessTokenExpiresAt;
        if (!$expiresAt){
            return true;
        }
        return now() >= $expiresAt;
    }
}
