<?php
/**
 * This file is part of Rocketgraph service
 * <http://www.rocketgraph.com>
 */

namespace RAM\Connectors;
use RG\Oauth1Connector;

/**
 * Description of FreshBooksConnector
 *
 * @author K.Christofilos <kostas.christofilos@rocketgraph.com>
 */
class FreshBooksConnector extends Oauth1Connector
{
    const API_HOST = 'http://www.freshbooks.com/api';
    const API_VERSION = '2.1';

    /**
     * Build absolute URL from path.
     *
     * @param $path
     *
     * @return string
     */
    public function buildUrlFromPath($path)
    {
        return sprintf("%s/%s", self::API_HOST, self::API_VERSION);
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
        return 'https://sample.freshbooks.com/oauth/oauth_request.php';
    }

    /**
     * Get the URL for redirecting the resource owner to authorize the client.
     *
     * @return string
     */
    public function urlAuthorization()
    {
        return 'https://sample.freshbooks.com/oauth/oauth_authorize.php';
    }

    /**
     * Get the URL retrieving token credentials.
     *
     * @return string
     */
    public function urlTokenCredentials()
    {
        return 'https://sample.freshbooks.com/oauth/oauth_access.php';
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
}