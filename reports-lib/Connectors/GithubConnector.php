<?php
/**
 * This file is part of Rocketgraph service
 * <http://www.rocketgraph.com>
 */

namespace RAM\Connectors;

use Doctrine\ORM\EntityManager;
use League\OAuth2\Client\Token\AccessToken;
use RG\ReportsBundle\Model\Oauth2Connector;
use RG\SubscriptionsBundle\Entity\Subscription;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Request;

/**
 * Description of GithubConnector
 *
 * @author K.Christofilos <kostas.christofilos@rocketgraph.com>
 */
class GithubConnector extends Oauth2Connector
{
    public $responseType = 'string';

    public $authorizationHeader = 'token';

    public $domain = 'https://github.com';

    public $apiDomain = 'https://api.github.com';

    public function urlAuthorize()
    {
        return $this->domain.'/login/oauth/authorize';
    }

    public function urlAccessToken()
    {
        return $this->domain.'/login/oauth/access_token';
    }

    public function urlUserDetails(AccessToken $token)
    {
        if ($this->domain === 'https://github.com') {
            return $this->apiDomain.'/user';
        }
        return $this->domain.'/api/v3/user';
    }

    public function urlUserEmails(AccessToken $token)
    {
        if ($this->domain === 'https://github.com') {
            return $this->apiDomain.'/user/emails';
        }
        return $this->domain.'/api/v3/user/emails';
    }

    public function userDetails($response, AccessToken $token)
    {
        $user = new User();

        $name = (isset($response->name)) ? $response->name : null;
        $email = (isset($response->email)) ? $response->email : null;

        $user->exchangeArray([
            'uid' => $response->id,
            'nickname' => $response->login,
            'name' => $name,
            'email' => $email,
            'urls'  => [
                'GitHub' => $this->domain.'/'.$response->login,
            ],
        ]);

        return $user;
    }

    public function userUid($response, AccessToken $token)
    {
        return $response->id;
    }

    public function getUserEmails(AccessToken $token)
    {
        $response = $this->fetchUserEmails($token);

        return $this->userEmails(json_decode($response), $token);
    }

    public function userEmail($response, AccessToken $token)
    {
        return isset($response->email) && $response->email ? $response->email : null;
    }

    public function userEmails($response, AccessToken $token)
    {
        return $response;
    }

    public function userScreenName($response, AccessToken $token)
    {
        return $response->name;
    }

    protected function fetchUserEmails(AccessToken $token)
    {
        $url = $this->urlUserEmails($token);

        $headers = $this->getHeaders($token);

        return $this->fetchProviderData($url, $headers);
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