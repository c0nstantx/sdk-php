<?php

/**
 * This file is part of Rocketgraph service
 * <http://www.rocketgraph.com>.
 */
namespace RG;

use Buzz\Browser;
use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Token\AccessToken;
use RG\Interfaces\ConnectorInterface;
use RG\Connection;
use RG\Traits\ConnectorTrait;

/**
 * Description of Oauth1Connector.
 *
 * @author K.Christofilos <kostas.christofilos@rocketgraph.com>
 */
abstract class Oauth2Connector extends AbstractProvider implements ConnectorInterface
{
    use ConnectorTrait;

    public function __construct(Browser $httpClient)
    {
        $this->client = $httpClient;
    }

    /**
     * {@inheritdoc}
     */
    public function buildToken(Connection $connection)
    {
        $token = new AccessToken([
            'access_token' => $connection->getAccessToken(),
            'refresh_token' => $connection->getRefreshToken(),
            'expires' => $connection->getExpires()
        ]);
        $this->setToken($token);
    }

    /**
     * {@inheritdoc}
     */
    public function get($path, $options = array())
    {
        $url = $this->buildUrlFromPath($path);
        $options['access_token'] = $this->token->accessToken;
        $query = http_build_query($options);
        if ($query !== '') {
            $url .= "?$query";
        }
        $headers = $this->buildHeaders($url);
        $response = $this->client->get($url, $headers);

        return json_decode($response->getContent());
    }

    /**
     * {@inheritdoc}
     */
    public function urlUserDetails(AccessToken $token)
    {
        // TODO: Implement urlUserDetails() method.
    }

    /**
     * {@inheritdoc}
     */
    public function userDetails($response, AccessToken $token)
    {
        // TODO: Implement userDetails() method.
    }

    /**
     * {@inheritdoc}
     */
    public function needsExtraParameters()
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function setupProvider()
    {
        parent::__construct(array(
            'clientId' => $this->key,
            'clientSecret' => $this->secret,
            'redirectUri' => $this->callbackUrl,
            'scopes' => $this->scopes,
        ));
    }

    /**
     * Build headers.
     *
     * @return array
     */
    protected function buildHeaders()
    {
        return $this->getHeaders($this->token);
    }
}
