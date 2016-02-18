<?php
/**
 * This file is part of Rocketgraph service
 * <http://www.rocketgraph.com>
 */

namespace RG;

use RAM\Connectors\Mock\NoAuthMockConnector;
use RAM\Connectors\NoAuthConnector;

/**
 * Description of ConnectorService
 *
 * @author K.Christofilos <kostas.christofilos@rocketgraph.com>
 */
class ConnectorService
{
    protected $connectors = [];

    protected $client;

    protected $proxy;

    protected $connection;

    protected $responses;

    public function __construct($connectors, Browser $httpClient, Proxy $proxy,
                                $connection = 'live', array $responses = [])
    {
        $this->client = $httpClient;
        $this->proxy = $proxy;
        $this->connection = $connection;
        $this->responses = $responses;
        foreach($connectors as $connector => $params) {
            $this->connectors[$connector] = $this->buildConnector($connector, $params);
        }
    }

    public function getAvailableConnectors()
    {
        $connectors =  [
            'twitter' => 'Twitter',
            'facebook' => 'Facebook',
            'facebookpage' => 'Facebook Page',
            'stripe' => 'Stripe',
            'dribbble' => 'Dribbble',
            'soundcloud' => 'Soundlcoud',
            'google' => 'Google+',
            'youtubedata' => 'Youtube Data',
            'youtubeanalytics' => 'Youtube Analytics',
            'quickbooks' => 'Quickbooks',
            'fitbit' => 'Fitbit',
            'github' => 'Github',
            'fivehundredpx' => '500px',
            'angellist' => 'AngelList',
            'behance' => 'Behance',
            'box' => 'Box',
            'dropbox' => 'Dropbox',
            'etsy' => 'Etsy',
            'flickr' => 'Flickr',
            'foursquare' => 'Foursquare',
            'freshbooks' => 'FreshBooks',
            'gmail' => 'Gmail',
            'googleanalytics' => 'Google Analytics',
            'instagram' => 'Instagram',
            'pinterest' => 'Pinterest',
            'spotify' => 'Spotify',
            'linkedin' => 'LinkedIn',
            'paypal' => 'Paypal',
            'slack' => 'Slack',
            'square' => 'Square',
            'trello' => 'Trello',
            'tumblr' => 'Tumblr',
            'twitch' => 'Twitch',
            'vimeo' => 'Vimeo',
            'jawbone' => 'Jawbone',
            'vk' => 'VK',
            'weibo' => 'Weibo',
            'meetup' => 'Meetup',
            'misfit' => 'Misfit',
            'withings' => 'Withings'
        ];

        asort($connectors, SORT_STRING);

        return $connectors;
    }

    /**
     * @return array
     */
    public function getConnectors()
    {
        return $this->connectors;
    }

    /**
     * @return NoAuthMockConnector|NoAuthConnector
     */
    public function buildOpenConnector()
    {
        if ($this->connection === 'sandbox') {
            $connector = new NoAuthMockConnector($this->client, $this->proxy);
            if (!isset($this->responses['open'])) {
                throw new \RuntimeException("You requested sandbox environment for open connector, but you haven't defined any responses in app/config/responses.yml");
            }
            $connector->setResponses($this->responses['open']);
            return $connector;
        } else {
            return new NoAuthConnector($this->client, $this->proxy);
        }
    }

    public function buildPrimitiveConnector($provider)
    {
        $connectorClass = ucfirst(strtolower($provider)).'Connector';
        $reflectionClass = new \ReflectionClass("RAM\\Connectors\\$connectorClass");

        require_once $reflectionClass->getFileName();
        return $reflectionClass->newInstance($this->client, $this->proxy);
    }

    /**
     * @param string $provider
     * @param array $params
     *
     * @return object
     */
    protected function buildConnector($provider, array $params)
    {
        if ($this->connection === 'sandbox') {
            $connectorClass = ucfirst(strtolower($provider)).'MockConnector';
            $reflectionClass = new \ReflectionClass("RAM\\Connectors\\Mock\\$connectorClass");
        } else if ($this->connection === 'live') {
            $connectorClass = ucfirst(strtolower($provider)).'Connector';
            $reflectionClass = new \ReflectionClass("RAM\\Connectors\\$connectorClass");
        } else {
            throw new \RuntimeException("No valid connection type was found. Valid types are 'sandbox' or 'live'");
        }
        require_once $reflectionClass->getFileName();
        $connector = $reflectionClass->newInstance($this->client, $this->proxy);

        if ($this->connection === 'live') {
            $connection = new Connection();

            if ($connector instanceof Oauth1Connector) {
                $connector->key = $params['api_key'];
                $connector->secret = $params['api_secret'];
                $connection->setIdentifier($params['access_token']);
                $connection->setSecret($params['access_token_secret']);
            } else {
                $connection->setAccessToken($params['access_token']);
            }
            $connector->buildToken($connection);
        } else {
            if (!isset($this->responses[$provider])) {
                throw new \RuntimeException("You requested sandbox environment for '$provider', but you haven't defined any responses in app/config/responses.yml");
            }
            $connector->setResponses($this->responses[$provider]);
        }
        return $connector;
    }
}