<?php

declare(strict_types=1);

namespace Richard\HyperfPassport\Bridge;

use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;
use Richard\HyperfPassport\Passport;

use function Hyperf\Collection\collect;
use function Hyperf\Support\make;

class ScopeRepository implements ScopeRepositoryInterface
{
    public function getScopeEntityByIdentifier($identifier): ?ScopeEntityInterface
    {
        $passport = make(Passport::class);
        if ($passport->hasScope($identifier)) {
            return new Scope($identifier);
        }
        return null;
    }

    public function finalizeScopes(
        array $scopes,
        $grantType,
        ClientEntityInterface $clientEntity,
        $userIdentifier = null,
        ?string $authCodeId = null
    ): array {
        if (! in_array($grantType, ['password', 'personal_access', 'client_credentials'])) {
            $scopes = collect($scopes)->reject(function ($scope) {
                return trim($scope->getIdentifier()) === '*';
            })->values()->all();
        }
        $passport = make(Passport::class);
        return collect($scopes)->filter(function ($scope) use ($passport) {
            return $passport->hasScope($scope->getIdentifier());
        })->values()->all();
    }
}
