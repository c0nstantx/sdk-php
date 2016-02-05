<?php
/**
 * This file is part of Rocketgraph service
 * <http://www.rocketgraph.com>
 */

namespace RAM\Connectors;
use RG\Oauth1Connector;

/**
 * Description of FlickrConnector
 *
 * @author K.Christofilos <kostas.christofilos@rocketgraph.com>
 */
class FlickrConnector extends Oauth1Connector
{
    const API_HOST = 'https://api.flickr.com/services/rest';

    /**
     * Build absolute URL from path.
     *
     * @param $path
     *
     * @return string
     */
    public function buildUrlFromPath($path)
    {
        return sprintf("%s/%s", self::API_HOST, $path);
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
        return 'https://www.flickr.com/services/oauth/request_token';
    }

    /**
     * Get the URL for redirecting the resource owner to authorize the client.
     *
     * @return string
     */
    public function urlAuthorization()
    {
        return 'https://www.flickr.com/services/oauth/authorize';
    }

    /**
     * Get the URL retrieving token credentials.
     *
     * @return string
     */
    public function urlTokenCredentials()
    {
        return 'https://www.flickr.com/services/oauth/access_token';
    }

    /**
     * Get the URL for retrieving user details.
     *
     * @return string
     */
    public function urlUserDetails()
    {
        // TODO: Implement urlUserDetails() method.
    }

    /**
     * @param $path
     * @param array $options
     *
     * @return string
     */
    protected function buildUrl($path, $options = [])
    {
        $options = array_merge($options, [
            'method' => $path,
            'format' => 'json',
        ]);
        return parent::buildUrl($path, $options);
    }
}