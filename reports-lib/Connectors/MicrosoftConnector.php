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
 * Description of MicrosoftConnector
 *
 * @author K.Christofilos <kostas.christofilos@rocketgraph.com>
 */
class MicrosoftConnector extends Oauth2Connector
{

    /**
     * Get the URL that this provider uses to begin authorization.
     *
     * @return string
     */
    public function urlAuthorize()
    {
        // TODO: Implement urlAuthorize() method.
    }

    /**
     * Get the URL that this provider users to request an access token.
     *
     * @return string
     */
    public function urlAccessToken()
    {
        // TODO: Implement urlAccessToken() method.
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