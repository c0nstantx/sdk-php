<?php

/**
 * This file is part of Rocketgraph service
 * <http://www.rocketgraph.com>.
 */
namespace RAM\Connectors;

use Buzz\Browser;
use RG\Oauth2Connector;

/**
 * Description of StripeConnector.
 *
 * @author K.Christofilos <kostas.christofilos@rocketgraph.com>
 */
class StripeConnector extends Oauth2Connector
{
    const API_HOST = 'https://api.stripe.com';

    const API_VERSION = 'v1';

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
        return 'https://connect.stripe.com/oauth/authorize';
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
        return 'https://connect.stripe.com/oauth/token';
    }
}
