<?php

/**
 * This file is part of Rocketgraph service
 * <http://www.rocketgraph.com>.
 */
namespace RAM\Connectors;

use RG\Oauth2Connector;

/**
 * Description of FitbitConnector.
 *
 * @author K.Christofilos <kostas.christofilos@rocketgraph.com>
 */
class FitbitConnector extends Oauth2Connector
{
    const API_HOST = 'https://api.fitbit.com';

    const API_VERSION = '1';

    protected $scopes = [
        'activity',
        'nutrition',
        'profile',
        'settings',
        'sleep',
        'social',
        'weight'
    ];
    /**
     * {@inheritdoc}
     */
    public function buildUrlFromPath($path)
    {
        return sprintf('%s/%s/%s', self::API_HOST, self::API_VERSION,
            $path);
    }

    /**
     * {@inheritdoc}
     */
    public function getDisplayName()
    {
        $profile = $this->get(sprintf('user/%s/profile.json', $this->getUserId()));
        if ($profile) {
            return $profile->displayName;
        }
        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function retrieveAuthUrl()
    {
        $authUrl = $this->getAuthorizationUrl();
        return $authUrl.'&scope='.implode(" ", $this->scopes);
    }

    /**
     * Returns an encoded user's id
     * If '-' returned info for current logged in user is fetched
     * @return string
     */
    public function getUserId(){
        return '-';
    }

    /**
     * Returns the base URL for authorizing a client.
     *
     * Eg. https://oauth.service.com/authorize
     *
     * @return string
     */
    public function getBaseAuthorizationUrl()
    {
        return 'https://www.fitbit.com/oauth2/authorize';
    }

    /**
     * Returns the base URL for requesting an access token.
     *
     * Eg. https://oauth.service.com/token
     *
     * @param array $params
     * @return string
     */
    public function getBaseAccessTokenUrl(array $params = [])
    {
        return 'https://api.fitbit.com/oauth2/token';
    }
}