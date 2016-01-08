<?php
/**
 * This file is part of Rocketgraph service
 * <http://www.rocketgraph.com>
 */

namespace RAM\Connectors;
use RG\Oauth1Connector;

/**
 * Description of TumblrConnector
 *
 * @author K.Christofilos <kostas.christofilos@rocketgraph.com>
 */
class TumblrConnector extends Oauth1Connector
{
    const API_HOST = 'https://api.tumblr.com';
    const API_VERSION = 'v2';

    /**
     * Build absolute URL from path.
     *
     * @param $path
     *
     * @return string
     */
    public function buildUrlFromPath($path)
    {
        return sprintf("%s/%s/%s", self::API_HOST, self::API_VERSION, $path);
    }

    /**
     * Returns the display name of connection
     *
     * @return string
     */
    public function getDisplayName()
    {
        return '';
    }

    /**
     * Get the URL for retrieving temporary credentials.
     *
     * @return string
     */
    public function urlTemporaryCredentials()
    {
        return 'https://www.tumblr.com/oauth/request_token';
    }

    /**
     * Get the URL for redirecting the resource owner to authorize the client.
     *
     * @return string
     */
    public function urlAuthorization()
    {
        return 'https://www.tumblr.com/oauth/authorize';
    }

    /**
     * Get the URL retrieving token credentials.
     *
     * @return string
     */
    public function urlTokenCredentials()
    {
        return 'https://www.tumblr.com/oauth/access_token';
    }

    /**
     * Get the URL for retrieving user details.
     *
     * @return string
     */
    public function urlUserDetails()
    {
        return;
    }
}