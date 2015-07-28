<?php

/**
 * This file is part of Rocketgraph service
 * <http://www.rocketgraph.com>.
 */
namespace RAM\Connectors;

use Buzz\Browser;
use RG\Oauth2Connector;
use Symfony\Component\HttpFoundation\Request;

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

    public function __construct(Browser $httpClient)
    {
        parent::__construct($httpClient);
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
     * Get the URL that this provider uses to begin authorization.
     *
     * @return string
     */
    public function urlAuthorize()
    {
        return 'https://www.facebook.com/'.$this->graphApiVersion.'/dialog/oauth';
    }

    /**
     * Get the URL that this provider users to request an access token.
     *
     * @return string
     */
    public function urlAccessToken()
    {
        return 'https://graph.facebook.com/'.$this->graphApiVersion.'/oauth/access_token';
    }
}
