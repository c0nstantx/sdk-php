<?php

/**
 * This file is part of Rocketgraph service
 * <http://www.rocketgraph.com>.
 */
namespace RAM\Connectors;

use RG\Oauth1Connector;
use Symfony\Component\HttpFoundation\Request;

/**
 * Description of TwitterConnector.
 *
 * @author K.Christofilos <kostas.christofilos@rocketgraph.com>
 */
class TwitterConnector extends Oauth1Connector
{
    const API_VERSION = '1.1';
    const API_HOST = 'https://api.twitter.com';
    const UPLOAD_HOST = 'https://upload.twitter.com';

    /**
     * {@inheritdoc}
     */
    public function buildUrlFromPath($path)
    {
        return sprintf('%s/%s/%s.json', self::API_HOST, self::API_VERSION, $path);
    }

    /**
     * {@inheritdoc}
     */
    public function getDisplayName()
    {
        $profile = $this->get('account/verify_credentials');
        if ($profile) {
            return $profile->screen_name;
        }
        return '';
    }
    /**
     * {@inheritdoc}
     */
    public function isResponse(Request $request)
    {
        return $request->get('oauth_token') && $request->get('oauth_verifier');
    }

    /**
     * {@inheritdoc}
     */
    public function urlTemporaryCredentials()
    {
        return 'https://api.twitter.com/oauth/request_token';
    }

    /**
     * {@inheritdoc}
     */
    public function urlAuthorization()
    {
        return 'https://api.twitter.com/oauth/authenticate';
    }

    /**
     * {@inheritdoc}
     */
    public function urlTokenCredentials()
    {
        return 'https://api.twitter.com/oauth/access_token';
    }

    /**
     * {@inheritdoc}
     */
    public function urlUserDetails()
    {
        return 'https://api.twitter.com/1.1/account/verify_credentials.json';
    }
}
