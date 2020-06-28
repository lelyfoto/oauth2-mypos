<?php

declare(strict_types=1);

namespace Lelyfoto\OAuth2\Client\Provider;

use League\OAuth2\Client\OptionProvider\HttpBasicAuthOptionProvider;
use League\OAuth2\Client\Provider\GenericProvider;
use League\OAuth2\Client\Token\AccessTokenInterface;
use Ramsey\Uuid\Uuid;

class MyPos extends GenericProvider
{
    protected const MYPOS_AUTH_URL = 'https://auth-api.mypos.com/oauth/';

    public function __construct(array $options = [], array $collaborators = [])
    {
        $collaborators['optionProvider'] = new HttpBasicAuthOptionProvider();

        $options['urlAccessToken'] = self::MYPOS_AUTH_URL . 'token';
        $options['urlAuthorize'] = self::MYPOS_AUTH_URL . 'authorize';
        $options['urlResourceOwnerDetails'] = self::MYPOS_AUTH_URL;

        parent::__construct($options, $collaborators);
    }

    /**
     * Returns the default headers used by this provider.
     *
     * Typically this is used to set 'Accept' or 'Content-Type' headers.
     *
     * @return array
     */
    protected function getDefaultHeaders(): array
    {
        return [
            'X-Request-ID' => Uuid::uuid4()->toString(),
        ];
    }

    /**
     * Returns authorization headers for the 'bearer' grant.
     *
     * @param  AccessTokenInterface|string|null $token Either a string or an access token instance
     * @return array
     */
    protected function getAuthorizationHeaders($token = null)
    {
        return array_merge(
            parent::getAuthorizationHeaders($token),
            [
                'API-Key' => $this->clientId
            ]
        );
    }
}
