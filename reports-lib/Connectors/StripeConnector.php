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
 * Description of StripeConnector.
 *
 * @author K.Christofilos <kostas.christofilos@rocketgraph.com>
 */
class StripeConnector extends Oauth2Connector
{
    const API_HOST = 'https://api.stripe.com';

    const API_VERSION = 'v1';

    public function __construct(Browser $httpClient)
    {
        $this->authorizationHeader = 'Bearer';
        parent::__construct($httpClient);
    }

    /**
     * {@inheritdoc}
     */
    public function get($path, $options = array())
    {
        $url = $this->buildUrlFromPath($path);
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
    public function buildUrlFromPath($path)
    {
        return sprintf('%s/%s/%s', self::API_HOST, self::API_VERSION,
            $path);
    }

    /**
     * {@inheritdoc}
     */
    public function getDisplayName()
    {
        $profile = $this->get('account');
        if ($profile) {
            return $profile->display_name;
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
        return 'https://connect.stripe.com/oauth/authorize';
    }

    /**
     * Get the URL that this provider users to request an access token.
     *
     * @return string
     */
    public function urlAccessToken()
    {
        return 'https://connect.stripe.com/oauth/token';
    }
}
