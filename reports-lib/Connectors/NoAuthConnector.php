<?php
/**
 * This file is part of Rocketgraph service
 * <http://www.rocketgraph.com>
 */

namespace RAM\Connectors;
use Buzz\Browser;
use RG\Connection;
use RG\Interfaces\ConnectorInterface;
use RG\Proxy;
use RG\Traits\ProxyConnectorTrait;

/**
 * Description of NoAuthConnector
 *
 * @author K.Christofilos <kostas.christofilos@rocketgraph.com>
 */
class NoAuthConnector implements ConnectorInterface
{
    use ProxyConnectorTrait;

    protected $client;

    public function __construct(Browser $httpClient, Proxy $proxy)
    {
        $this->client = $httpClient;
        $this->proxy = $proxy;
        $this->userAgent = 'Rocketgraph-engine';
    }

    /**
     * Get result from API
     *
     * @param string $url
     * @param array $options
     * @param bool $array
     * @param bool $useProxy
     * @param bool $permanent
     * @param bool $force
     *
     * @return mixed
     */
    public function get($path, $options = array(), $array = false, $useProxy = true,
                        $permanent = false, $force = false)
    {
        $url = $this->buildUrl($path, $options);
        $headers = $this->buildHeaders($url);

        if ($useProxy) {
            return $this->getFromProxy($path, $options, $array ,$permanent, $force);
        }
        $response = $this->client->get($url, $headers);

        return json_decode($response->getContent(), $array);
    }

    public function getName()
    {
        return 'open';
    }

    protected function buildUrl($url, $options = [])
    {
        $query = http_build_query($options);
        if ($query !== '') {
            $url .= "?$query";
        }
        return $url;
    }

    protected function buildHeaders($url)
    {
        return ['User-Agent' => $this->userAgent];
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
     * Connect to Provider.
     */
    public function setupProvider()
    {
        // TODO: Implement setupProvider() method.
    }

    /**
     * Build connector token, based on subscription's connection
     *
     * @param Connection $connection
     */
    public function buildToken(Connection $connection)
    {
        // TODO: Implement buildToken() method.
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

    /**
     * Returns if connector needs extra parameters
     *
     * @return bool
     */
    public function needsExtraParameters()
    {
        // TODO: Implement needsExtraParameters() method.
    }
}