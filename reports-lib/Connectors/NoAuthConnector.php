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
use RG\Traits\ConnectorTrait;
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

    protected $lastHeaders = [];

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
    public function get($path, array $options = [], array $headers = [],
                        $array = false, $useProxy = true, $permanent = false,
                        $force = false
    )
    {
        $path = ConnectorTrait::sanitizePath($path);
        $url = $this->buildUrl($path, $options);
        $requestHeaders = $this->buildHeaders($url, $headers);

        if ($useProxy) {
            return $this->getFromProxy($path, $options, $requestHeaders, $array ,$permanent, $force);
        } else {
            $response = $this->client->get($url, $requestHeaders);
            $lastResponse = $this->client->getLastResponse();
            if ($lastResponse) {
                $headers = $lastResponse->getHeaders();
                $this->lastHeaders = $this->parseHeaders($headers);
            } else {
                $this->lastHeaders = [];
            }
            return ConnectorTrait::convertContent($response->getContent(), $array);
        }
    }

    public function getName()
    {
        return 'open';
    }

    /**
     * @param string $path
     * @param array $options
     *
     * @return string
     */
    protected function buildUrl($path, $options = [])
    {
        $query = http_build_query($options);
        $url = $this->buildUrlFromPath($path);
        if ($query !== '') {
            $url .= "?$query";
        }
        return $url;
    }

    /**
     * @param array $extraHeaders
     *
     * @return array
     */
    protected function buildHeaders($url, array $extraHeaders = [])
    {
        $defaultHeaders = ['User-Agent' => $this->userAgent];

        return array_merge($defaultHeaders, $extraHeaders);
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
        return $path;
    }

    /**
     * Get latest call headers
     *
     * @return array
     */
    public function getLastHeaders()
    {
        return $this->lastHeaders;
    }

    /**
     * Parse response headers
     *
     * @param array $headers
     * @return array
     */
    protected function parseHeaders(array $headers)
    {
        $parsedHeaders = [];
        foreach($headers as $header) {
            $firstSeparator = strpos($header, ':');
            if ($firstSeparator) {
                $key = substr($header, 0, $firstSeparator);
                $value = substr($header, $firstSeparator+1);
                $parsedHeaders[strtolower($key)] = strtolower(trim($value));
            } else {
                $parsedHeaders[strtolower($header)] = true;
            }
        }
        return $parsedHeaders;
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