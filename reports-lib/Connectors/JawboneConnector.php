<?php
/**
 * This file is part of Rocketgraph service
 * <http://www.rocketgraph.com>
 */

namespace RAM\Connectors;
use RG\Oauth2Connector;

/**
 * Description of JawboneConnector
 *
 * @author K.Christofilos <kostas.christofilos@rocketgraph.com>
 */
class JawboneConnector extends Oauth2Connector
{
    const API_HOST = 'https://jawbone.com/nudge/api';

    const API_VERSION = 'v.1.1';

    /**
     * Returns the base URL for authorizing a client.
     *
     * Eg. https://oauth.service.com/authorize
     *
     * @return string
     */
    public function getBaseAuthorizationUrl()
    {
        return 'https://jawbone.com/auth/oauth2/auth';
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
        return 'https://jawbone.com/auth/oauth2/token';
    }

    /**
     * Build absolute URL from path.
     *
     * @param $path
     *
     * @return string
     */
    public function buildUrlFromPath($path)
    {
        return sprintf('%s/%s/%s', self::API_HOST, self::API_VERSION, $path);
    }
}