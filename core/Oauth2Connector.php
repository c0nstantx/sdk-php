<?php

/**
 * This file is part of Rocketgraph service
 * <http://www.rocketgraph.com>.
 */
namespace RG;

use Buzz\Browser;
use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Token\AccessToken;
use Psr\Http\Message\ResponseInterface;
use RG\Interfaces\ConnectorInterface;
use RG\Traits\ConnectorTrait;
use RG\Traits\ProxyConnectorTrait;

/**
 * Description of Oauth1Connector.
 *
 * @author K.Christofilos <kostas.christofilos@rocketgraph.com>
 */
abstract class Oauth2Connector extends AbstractProvider implements ConnectorInterface
{
    use ProxyConnectorTrait;
    use ConnectorTrait {
        ConnectorTrait::__construct as private __cConstruct;
    }

    protected $scopes = [];

    protected $tokenName = 'access_token';

    public function __construct(Browser $httpClient, Proxy $proxy)
    {
        $this->__cConstruct();
        $this->client = $httpClient;
        $this->proxy = $proxy;
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
    public function get($path, array $options = [], array $headers = [],
                        $array = false, $useProxy = true, $permanent = false,
                        $force = false
    )
    {
        $path = self::sanitizePath($path);
        if ($this->tokenName) {
            $options[$this->tokenName] = $this->token->getToken();
        }
        $url = $this->buildUrl($path, $options);
        $requestHeaders = $this->buildHeaders($url, $headers);

        if ($useProxy) {
            return $this->getFromProxy($path, $options, $requestHeaders, $array ,$permanent, $force);
        } else {
            $response = $this->client->get($url, $requestHeaders);
            $lastResponse = $this->client->getLastResponse();
            if ($lastResponse) {
                $headers = $lastResponse->getHeaders();
                $this->lastHeaders = $this->parseHeaders($headers);
            } else {
                $this->lastHeaders = [];
            }
            return json_decode($response->getContent(), $array);
        }

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
     * @param string $url
     * @param array  $extraHeaders
     *
     * @return array
     */
    protected function buildHeaders($url, array $extraHeaders = [])
    {
        $headers = $this->getHeaders($this->token);
        $headers['Accept'] = 'application/json';

        return array_merge($headers, $extraHeaders);
    }

    /**
     * Returns the authorization headers used by this provider.
     *
     * Typically this is "Bearer" or "MAC". For more information see:
     * http://tools.ietf.org/html/rfc6749#section-7.1
     *
     * No default is provided, providers must overload this method to activate
     * authorization headers.
     *
     * @param  mixed|null $token Either a string or an access token instance
     * @return array
     */
    protected function getAuthorizationHeaders($token = null)
    {
        return ['Authorization' => "Bearer {$token->getToken()}"];
    }

    /**
     * Checks if a token is invalid
     *
     * @param AccessToken $token
     *
     * @return bool
     */
    protected function tokenIsInvalid(AccessToken $token)
    {
        return false;
    }

    /**
     * Returns the URL for requesting the resource owner's details.
     *
     * @param AccessToken $token
     * @return string
     */
    public function getResourceOwnerDetailsUrl(AccessToken $token)
    {
        // TODO: Implement getResourceOwnerDetailsUrl() method.
    }

    /**
     * Returns the default scopes used by this provider.
     *
     * This should only be the scopes that are required to request the details
     * of the resource owner, rather than all the available scopes.
     *
     * @return array
     */
    protected function getDefaultScopes()
    {
        return $this->scopes;
    }

    /**
     * Checks a provider response for errors.
     *
     * @throws IdentityProviderException
     * @param  ResponseInterface $response
     * @param  array|string $data Parsed response data
     * @return void
     */
    protected function checkResponse(ResponseInterface $response, $data)
    {
        // TODO: Implement checkResponse() method.
    }

    /**
     * Generates a resource owner object from a successful resource owner
     * details request.
     *
     * @param  array $response
     * @param  AccessToken $token
     * @return ResourceOwnerInterface
     */
    protected function createResourceOwner(array $response, AccessToken $token)
    {
        // TODO: Implement createResourceOwner() method.
    }
}
