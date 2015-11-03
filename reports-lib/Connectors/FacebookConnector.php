<?php

/**
 * This file is part of Rocketgraph service
 * <http://www.rocketgraph.com>.
 */
namespace RAM\Connectors;

use Buzz\Browser;
use Psr\Http\Message\ResponseInterface;
use RG\Oauth2Connector;
use RG\Proxy;

/**
 * Description of FacebookConnector.
 *
 * @author K.Christofilos <kostas.christofilos@rocketgraph.com>
 */
class FacebookConnector extends Oauth2Connector
{
    /**
     * @const string The fallback Graph API version to use for requests.
     */
    const DEFAULT_GRAPH_VERSION = 'v2.2';

    /**
     * @const string API's base url
     */
    const API_HOST = 'https://graph.facebook.com';

    /**
     * @var string The Graph API version to use for requests.
     */
    protected $graphApiVersion;

    public $scopes = ['public_profile', 'email'];

    public $responseType = 'string';

    public function __construct(Browser $httpClient, Proxy $proxy)
    {
        parent::__construct($httpClient, $proxy);
        if (null === $this->graphApiVersion) {
            $this->graphApiVersion = self::DEFAULT_GRAPH_VERSION;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function buildUrlFromPath($path)
    {
        return sprintf('%s/%s/%s', self::API_HOST, self::DEFAULT_GRAPH_VERSION,
            $path);
    }

    /**
     * {@inheritdoc}
     */
    public function getDisplayName()
    {
        $profile = $this->get('/me');
        if ($profile) {
            return $profile->name;
        }
        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function retrieveAuthUrl()
    {
        $authUrl = $this->getAuthorizationUrl();
        return $authUrl.'&display=popup';
    }

    /**
     * Returns the base URL for authorizing a client.
     *
     * Eg. https://oauth.service.com/authorize
     *
     * @return string
     */
    public function getBaseAuthorizationUrl()
    {
        return 'https://www.facebook.com/'.$this->graphApiVersion.'/dialog/oauth';
    }

    /**
     * Returns the base URL for requesting an access token.
     *
     * Eg. https://oauth.service.com/token
     *
     * @param array $params
     * @return string
     */
    public function getBaseAccessTokenUrl(array $params)
    {
        return 'https://graph.facebook.com/'.$this->graphApiVersion.'/oauth/access_token';
    }

    /**
     * @inheritdoc
     */
    protected function getContentType(ResponseInterface $response)
    {
        $type = parent::getContentType($response);
        // Fix for Facebook's pseudo-JSONP support
        if (strpos($type, 'javascript') !== false) {
            return 'application/json';
        }
        // Fix for Facebook's pseudo-urlencoded support
        if (strpos($type, 'plain') !== false) {
            return 'application/x-www-form-urlencoded';
        }
        return $type;
    }
}
