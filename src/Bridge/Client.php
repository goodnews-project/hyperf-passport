<?php

declare(strict_types=1);

namespace Richard\HyperfPassport\Bridge;

use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\Traits\ClientTrait;

class Client implements ClientEntityInterface
{
    use ClientTrait;

    /**
     * The client's provider.
     *
     * @var string
     */
    public $provider;

    /**
     * The client identifier.
     *
     * @var string
     */
    protected $identifier;

    /**
     * Create a new client instance.
     *
     * @param string $identifier
     * @param string $name
     * @param string $redirectUri
     * @param bool $isConfidential
     * @param null|string $provider
     */
    public function __construct($identifier, $name, $redirectUri, $isConfidential = false, $provider = null)
    {
        $this->setIdentifier((string) $identifier);

        $this->name = $name;
        $this->isConfidential = $isConfidential;
        $this->redirectUri = explode(',', $redirectUri);
        $this->provider = $provider;
    }

    /**
     * Get the client's identifier.
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * Set the client's identifier.
     *
     * @param string $identifier
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;
    }
}
