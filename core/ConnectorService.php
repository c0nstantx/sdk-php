<?php
/**
 * This file is part of Rocketgraph service
 * <http://www.rocketgraph.com>
 */

namespace RG;
use Buzz\Browser;
use Symfony\Component\Process\Exception\RuntimeException;

/**
 * Description of ConnectorService
 *
 * @author K.Christofilos <kostas.christofilos@rocketgraph.com>
 */
class ConnectorService
{
    protected $connectors = [];

    protected $client;

    protected $connection;

    public function __construct($connectors, Browser $httpClient, $connection = 'live')
    {
        $this->client = $httpClient;
        $this->connection = $connection;
        foreach($connectors as $connector => $params) {
            $this->connectors[$connector] = $this->buildConnector($connector, $params);
        }
    }

    public function getConnectors()
    {
        return $this->connectors;
    }

    protected function buildConnector($provider, array $params)
    {
        if ($this->connection === 'sandbox') {
            $connectorClass = ucfirst(strtolower($provider)).'MockConnector';
            $reflectionClass = new \ReflectionClass("RAM\\Connectors\\Mock\\$connectorClass");
        } else if ($this->connection === 'live') {
            $connectorClass = ucfirst(strtolower($provider)).'Connector';
            $reflectionClass = new \ReflectionClass("RAM\\Connectors\\$connectorClass");
        } else {
            throw new RuntimeException("No valid connection type was found. Valid types are 'sandbox' or 'live'");
        }
        require_once $reflectionClass->getFileName();
        $connector = $reflectionClass->newInstance($this->client);

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
        return $connector;
    }
}