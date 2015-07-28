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
use Symfony\Component\HttpFoundation\Request;

/**
 * Description of LinkedinConnector
 *
 * @author K.Christofilos <kostas.christofilos@rocketgraph.com>
 */
class LinkedinConnector extends Oauth2Connector
{
    public $scopes = ['r_basicprofile r_emailaddress r_contactinfo'];
    public $responseType = 'json';
    public $authorizationHeader = 'Bearer';
    public $fields = [
    'id', 'email-address', 'first-name', 'last-name', 'headline',
    'location', 'industry', 'picture-url', 'public-profile-url',
];

    public function urlAuthorize()
{
    return 'https://www.linkedin.com/uas/oauth2/authorization';
}

    public function urlAccessToken()
{
    return 'https://www.linkedin.com/uas/oauth2/accessToken';
}

    public function urlUserDetails(AccessToken $token)
{
    $fields = implode(',', $this->fields);
    return 'https://api.linkedin.com/v1/people/~:(' . $fields . ')?format=json';
}

    public function userDetails($response, AccessToken $token)
{
    $user = new User();

    $email = (isset($response->emailAddress)) ? $response->emailAddress : null;
    $location = (isset($response->location->name)) ? $response->location->name : null;
    $description = (isset($response->headline)) ? $response->headline : null;
    $pictureUrl = (isset($response->pictureUrl)) ? $response->pictureUrl : null;

    $user->exchangeArray([
        'uid' => $response->id,
        'name' => $response->firstName.' '.$response->lastName,
        'firstname' => $response->firstName,
        'lastname' => $response->lastName,
        'email' => $email,
        'location' => $location,
        'description' => $description,
        'imageurl' => $pictureUrl,
        'urls' => $response->publicProfileUrl,
    ]);

    return $user;
}

    public function userUid($response, AccessToken $token)
{
    return $response->id;
}

    public function userEmail($response, AccessToken $token)
{
    return isset($response->emailAddress) && $response->emailAddress
        ? $response->emailAddress
        : null;
}

    public function userScreenName($response, AccessToken $token)
{
    return [$response->firstName, $response->lastName];
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