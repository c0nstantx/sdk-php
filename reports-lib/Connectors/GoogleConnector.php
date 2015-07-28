<?php

/**
 * This file is part of Rocketgraph service
 * <http://www.rocketgraph.com>.
 */

namespace RAM\Connectors;

use RG\ReportsBundle\Model\Oauth2Connector;
use RG\SubscriptionsBundle\Entity\Subscription;
use RG\UserBundle\Interfaces\RGUserInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
use RAM\BaseReport;

/**
 * Description of GoogleConnector.
 *
 * @author K.Christofilos <kostas.christofilos@rocketgraph.com>
 */
class GoogleConnector extends Oauth2Connector
{
    public $scopeSeparator = ' ';

    const API_URL = 'https://www.googleapis.com/plus/v1';

    /**
     * Get the URL that this provider uses to begin authorization.
     *
     * @return string
     */
    public function urlAuthorize()
    {
        return 'https://accounts.google.com/o/oauth2/auth';
    }

    /**
     * Get the URL that this provider users to request an access token.
     *
     * @return string
     */
    public function urlAccessToken()
    {
        return 'https://accounts.google.com/o/oauth2/token';
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
        return self::API_URL.'/'.$path;
    }

    /**
     * Get authorization URL.
     */
    public function retrieveAuthUrl()
    {
        return $this->getAuthorizationUrl();
    }

    /**
     * Create/Updates a connection for a user-report combo.
     *
     * @param Request         $request
     * @param Subscription    $subscription
     * @param EntityManager   $em
     *
     * @return string
     */
    public function createConnection(Request $request, Subscription $subscription,
                                     EntityManager $em)
    {
        $token = $this->getAccessToken('authorization_code',
            ['code' => $request->get('code')]);
        $this->token = $token;

        $connectionRepo = $em->getRepository('RGSubscriptionsBundle:Connection');
        $reportConnector = $subscription->getReport()
            ->findConnectorByName($this->getName());
        $name = $this->getDisplayName();
        $connectionRepo->createOauth2Connection($subscription, $reportConnector,
            $token, $name);

        return $token;
    }

    /**
     * {@inheritdoc}
     */
    public function getDisplayName()
    {
        $profile = $this->get('people/me');
        if ($profile) {
            return $profile->displayName;
        }
        return '';
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
        return (boolean) $request->get('code');
    }
}
