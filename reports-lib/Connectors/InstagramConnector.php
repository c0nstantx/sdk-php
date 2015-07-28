<?php

/**
 * This file is part of Rocketgraph service
 * <http://www.rocketgraph.com>.
 */
namespace RAM\Connectors;

use Doctrine\ORM\EntityManager;
use RG\SubscriptionsBundle\Entity\Subscription;
use RG\UserBundle\Interfaces\RGUserInterface;
use RG\ReportsBundle\Model\Oauth2Connector;
use Symfony\Component\HttpFoundation\Request;
use RAM\BaseReport;

/**
 * Description of InstagramConnector.
 *
 * @author K.Christofilos <kostas.christofilos@rocketgraph.com>
 */
class InstagramConnector extends Oauth2Connector
{
    public $scopes = ['basic'];
    public $responseType = 'json';

    /**
     * {@inheritdoc}
     */
    public function buildUrlFromPath($path)
    {
        return "https://api.instagram.com/v1/$path";
    }

    /**
     * {@inheritdoc}
     */
    public function createConnection(Request $request, Subscription $subscription,
                                     EntityManager $em)
    {
        $token = $this->getAccessToken('authorization_code',
            ['code' => $request->get('code')]);
        $this->token = $token;

        $connectionRepo = $em->getRepository('RGSubscriptionsBundle:Connection');
        $reportConnector = $subscription->getReport()->findConnectorByName($this->getName());
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
        $profile = $this->get('users/self');
        if ($profile) {
            return $profile->data->username;
        }
        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function retrieveAuthUrl()
    {
        return $this->getAuthorizationUrl();
    }

    /**
     * {@inheritdoc}
     */
    public function isResponse(Request $request)
    {
        return (boolean) $request->get('code');
    }

    /**
     * Get the URL that this provider uses to begin authorization.
     *
     * @return string
     */
    public function urlAuthorize()
    {
        return 'https://api.instagram.com/oauth/authorize';
    }

    /**
     * Get the URL that this provider users to request an access token.
     *
     * @return string
     */
    public function urlAccessToken()
    {
        return 'https://api.instagram.com/oauth/access_token';
    }
}
