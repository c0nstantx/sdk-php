<?php
/**
 * This file is part of Rocketgraph service
 * <http://www.rocketgraph.com>
 */

namespace RAM\Connectors;
use RG\Oauth2Connector;

/**
 * Description of PowerbiConnector
 *
 * @author K.Christofilos <kostas.christofilos@rocketgraph.com>
 */
class PowerbiConnector extends Oauth2Connector
{
    const API_HOST = 'https://api.powerbi.com';
    const API_VERSION = 'beta';

    const RESOURCE_URI = 'https://analysis.windows.net/powerbi/api';

    /**
     * Returns the base URL for authorizing a client.
     *
     * Eg. https://oauth.service.com/authorize
     *
     * @return string
     */
    public function getBaseAuthorizationUrl()
    {
        return 'https://login.windows.net/common/oauth2/authorize';
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
        return 'https://login.microsoftonline.com/common/oauth2/token';
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
        return sprintf("%s/%s/%s", self::API_HOST, self::API_VERSION, $path);
    }

    protected function getAuthorizationParameters(array $options)
    {
        $params = parent::getAuthorizationParameters($options);
        $params['resource'] = self::RESOURCE_URI;

        return $params;
    }
}