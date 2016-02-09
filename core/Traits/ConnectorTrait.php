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

    public $scopes = [];

    public $callbackUrl = '';

    protected $connectorName;

    protected $token;

    protected $client;

    protected $lastHeaders = [];

    /**
     * Sanitize path before passing it to url building
     *
     * @param string $path
     *
     * @return string
     */
    public static function sanitizePath($path)
    {
        return ltrim($path, '/');
    }

    /**
     * Append query to url if any options are defined
     *
     * @param string $url
     * @param array $options
     *
     * @return string
     */
    public static function bindUrlOptions($url, array $options = [])
    {
        $query = http_build_query($options);
        if ($query !== '') {
            $url .= "?$query";
        }

        return $url;
    }

    /**
     * Converts response content based on the type (XML, JSON, RAW)
     *
     * @param string $content
     * @param bool $array
     *
     * @return mixed
     */
    public static function convertContent($content, $array = false)
    {
        if (self::isXML($content)) {
            $content = self::XMLtoJSON($content);
            return json_decode($content, $array);
        } elseif (self::isJson($content)) {
            return json_decode($content, $array);
        } else {
            return $content;
        }
    }

    /**
     * Returns if the given content is a valid XML
     *
     * @param string $content
     *
     * @return bool
     */
    public static function isXML($content)
    {
        libxml_use_internal_errors(true);
        $doc = simplexml_load_string($content);

        return (bool)$doc;
    }

    /**
     * Returns if the given content is a valid JSON
     *
     * @param string $content
     *
     * @return bool
     */
    public static function isJson($content)
    {
        $json = json_decode($content);

        return $json !== null;
    }

    /**
     * Converts an XML string to JSON
     *
     * @param string $xml
     *
     * @return string
     */
    public static function XMLtoJSON($xml)
    {
        $xml = str_replace(array("\n", "\r", "\t"), '', $xml);
        $xml = trim(str_replace('"', "'", $xml));

        $simpleXml = simplexml_load_string($xml);

        return json_encode($simpleXml);
    }

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
        $path = self::sanitizePath($path);
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }
        $url = $this->buildUrlFromPath($path);

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
