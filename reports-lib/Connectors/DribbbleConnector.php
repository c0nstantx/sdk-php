<?php

/**
 * This file is part of Rocketgraph service
 * <http://www.rocketgraph.com>.
 */
namespace RAM\Connectors;

use Buzz\Browser;
use RG\Oauth2Connector;

/**
 * Description of DribbbleConnector.
 *
 * @author K.Christofilos <kostas.christofilos@rocketgraph.com>
 */
class DribbbleConnector extends Oauth2Connector
{
    const API_HOST = 'https://api.dribbble.com';

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
     * Returns the base URL for authorizing a client.
     *
     * Eg. https://oauth.service.com/authorize
     *
     * @return string
     */
    public function getBaseAuthorizationUrl()
    {
        return 'https://dribbble.com/oauth/authorize';
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
        return 'https://dribbble.com/oauth/token';
    }
}
