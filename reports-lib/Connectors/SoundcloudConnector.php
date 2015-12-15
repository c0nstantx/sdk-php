<?php

/**
 * This file is part of Rocketgraph service
 * <http://www.rocketgraph.com>.
 */
namespace RAM\Connectors;

use RG\Oauth2Connector;

/**
 * Description of SoundcloudConnector.
 *
 * @author K.Christofilos <kostas.christofilos@rocketgraph.com>
 */
class SoundcloudConnector extends Oauth2Connector
{
    const API_HOST = 'https://api.soundcloud.com';

    protected $tokenName = 'oauth_token';

    /**
     * {@inheritdoc}
     */
    public function buildUrlFromPath($path)
    {
        return sprintf('%s/%s', self::API_HOST, $path);
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
        return 'https://soundcloud.com/connect';
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
        return 'https://soundcloud.com/oauth2/token';
    }
}
