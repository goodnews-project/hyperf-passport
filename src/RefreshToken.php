<?php

namespace Richard\HyperfPassport;

use Hyperf\DbConnection\Model\Model;
use function Hyperf\Support\make;
use function Hyperf\Config\config;

class RefreshToken extends Model {

    /**
     * The database table used by the model.
     *
     * @var string|null
     */
    protected ?string $table = 'oauth_refresh_tokens';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public bool $incrementing = false;

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected string $keyType = 'string';

    /**
     * The guarded attributes on the model.
     *
     * @var array
     */
    protected array $guarded = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected array $casts = [
        'revoked' => 'bool',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected array $dates = [
        'expires_at',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public bool $timestamps = false;

    /**
     * Get the access token that the refresh token belongs to.
     *
     * @return \Hyperf\Database\Model\Relations\BelongsTo
     */
    public function accessToken() {
        $passport = make(\Richard\HyperfPassport\Passport::class);
        return $this->belongsTo($passport->tokenModel());
    }

    /**
     * Revoke the token instance.
     *
     * @return bool
     */
    public function revoke() {
        return $this->forceFill(['revoked' => true])->save();
    }

    /**
     * Determine if the token is a transient JWT token.
     *
     * @return bool
     */
    public function transient() {
        return false;
    }

    /**
     * Get the current connection name for the model.
     *
     * @return string|null
     */
    public function getConnectionName() {
        return config('passport.storage.database.connection') ?? $this->connection;
    }

}
