<?php
/**
 * This file is part of Rocketgraph service
 * <http://www.rocketgraph.com>
 */

namespace RAM\Connectors;
use Doctrine\ORM\EntityManager;
use RG\ReportsBundle\Model\Oauth2Connector;
use RG\SubscriptionsBundle\Entity\Subscription;
use Symfony\Component\HttpFoundation\Request;

/**
 * Description of DropboxConnector
 *
 * @author K.Christofilos <kostas.christofilos@rocketgraph.com>
 */
class DropboxConnector extends Oauth2Connector
{

    public function urlAuthorize()
    {
        return 'https://www.dropbox.com/1/oauth2/authorize';
    }
    public function urlAccessToken()
    {
        return 'https://api.dropbox.com/1/oauth2/token';
    }
    public function urlUserDetails(\League\OAuth2\Client\Token\AccessToken $token)
    {
        return 'https://api.dropbox.com/1/account/info?access_token='.$token;
    }
    public function userDetails($response, \League\OAuth2\Client\Token\AccessToken $token)
    {
        $user = new \stdClass();
        $user->uid = $response->uid;
        $user->name = $response->display_name;
        $user->email = $response->email;
        return $user;
    }
    public function getAuthorizationUrl($options = array())
    {
        return parent::getAuthorizationUrl(array_merge([
            'approval_prompt' => []
        ], $options));
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
     * Get authorization URL.
     */
    public function retrieveAuthUrl()
    {
        // TODO: Implement retrieveAuthUrl() method.
    }

    /**
     * Create/Updates a connection for a user-report combo.
     *
     * @param Request $request
     * @param Subscription $subscription
     * @param EntityManager $em
     *
     * @return string
     */
    public function createConnection(Request $request, Subscription $subscription,
                                     EntityManager $em)
    {
        // TODO: Implement createConnection() method.
    }

    /**
     * Checks if the request is a redirect response from Twitter.
     *
     * @param Request $request
     *
     * @return bool
     */
    public function isResponse(Request $request)
    {
        // TODO: Implement isResponse() method.
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