<?php
/**
 * This file is part of Rocketgraph service
 * <http://www.rocketgraph.com>
 */

namespace RAM\Connectors;
use RG\Oauth2Connector;

/**
 * Description of MailchimpConnector
 *
 * @author K.Christofilos <kostas.christofilos@rocketgraph.com>
 */
class MailchimpConnector extends Oauth2Connector
{

    /**
     * Returns the base URL for authorizing a client.
     *
     * Eg. https://oauth.service.com/authorize
     *
     * @return string
     */
    public function getBaseAuthorizationUrl()
    {
        return 'https://login.mailchimp.com/oauth2/authorize';
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
        return 'https://login.mailchimp.com/oauth2/token';
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
        // TODO: Implement buildUrlFromPath() method.
    }

    /**
     * Returns the display name of connection
     *
     * @return string
     */
    public function getDisplayName()
    {
        // TODO: Implement getDisplayName() method.
    }
}