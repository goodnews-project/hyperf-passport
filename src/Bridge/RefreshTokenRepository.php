<?php

declare(strict_types=1);

namespace Richard\HyperfPassport\Bridge;

use Hyperf\Database\Connection;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Richard\HyperfPassport\Event\RefreshTokenCreated;
use Richard\HyperfPassport\RefreshTokenRepository as PassportRefreshTokenRepository;

class RefreshTokenRepository implements RefreshTokenRepositoryInterface
{
    /**
     * The refresh token repository instance.
     *
     * @var Connection
     */
    protected $refreshTokenRepository;

    /**
     * The event dispatcher instance.
     *
     * @var EventDispatcherInterface
     */
    protected $events;

    /**
     * Create a new repository instance.
     */
    public function __construct(PassportRefreshTokenRepository $refreshTokenRepository, EventDispatcherInterface $events)
    {
        $this->events = $events;
        $this->refreshTokenRepository = $refreshTokenRepository;
    }

    public function getNewRefreshToken(): RefreshToken
    {
        return new RefreshToken();
    }

    public function persistNewRefreshToken(RefreshTokenEntityInterface $refreshTokenEntity): void
    {
        $this->refreshTokenRepository->create([
            'id' => $id = $refreshTokenEntity->getIdentifier(),
            'access_token_id' => $accessTokenId = $refreshTokenEntity->getAccessToken()->getIdentifier(),
            'revoked' => false,
            'expires_at' => $refreshTokenEntity->getExpiryDateTime(),
        ]);
        $this->events->dispatch(new RefreshTokenCreated($id, $accessTokenId));
    }

    public function revokeRefreshToken($tokenId): void
    {
        $this->refreshTokenRepository->revokeRefreshToken($tokenId);
    }

    public function isRefreshTokenRevoked($tokenId): bool
    {
        return $this->refreshTokenRepository->isRefreshTokenRevoked($tokenId);
    }
}
