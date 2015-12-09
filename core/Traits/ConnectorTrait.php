<?php

/**
 * This file is part of Rocketgraph service
 * <http://www.rocketgraph.com>.
 */

namespace RG\Traits;

/**
 * Description of ConnectorTrait.
 *
 * @author K.Christofilos <kostas.christofilos@rocketgraph.com>
 */
trait ConnectorTrait
{
    public $key = 'key';

    public $secret = 'secret';

    protected $connectorName;

    protected $token;

    protected $client;

    protected $lastHeaders = [];

    public function __construct()
    {
        $this->userAgent = 'Rocketgraph-engine';
    }

    public function __toString()
    {
        return $this->connectorName;
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set connector's token.
     *
     * @param mixed $token
     *
     * @return $this
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Return connector canonical name.
     *
     * @return string
     */
    public function getName()
    {
        $reflection = new \ReflectionClass($this);

        return strtolower(str_replace('Connector', '', $reflection->getShortName()));
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
     * @param $path
     * @param array $options
     *
     * @return string
     */
    protected function buildUrl($path, $options = [])
    {
        $url = $this->buildUrlFromPath($path);
        $query = http_build_query($options);
        if ($query !== '') {
            $url .= "?$query";
        }
        return $url;
    }

    /**
     * Returns the default headers used by this provider.
     *
     * Typically this is used to set 'Accept' or 'Content-Type' headers.
     *
     * @return array
     */
    protected function getDefaultHeaders()
    {
        return [
            'User-Agent' => $this->userAgent
        ];
    }
}
