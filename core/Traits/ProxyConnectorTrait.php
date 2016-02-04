<?php
/**
 * This file is part of Rocketgraph service
 * <http://www.rocketgraph.com>
 */

namespace RG\Traits;

/**
 * Description of ProxyConnectorTrait
 *
 * @author K.Christofilos <kostas.christofilos@rocketgraph.com>
 */
trait ProxyConnectorTrait
{
    protected $proxy;

    /**
     * Get latest call headers
     *
     * @return array
     */
    public function getLastProxyHeaders()
    {
        if ($this->lastProxyHeaders) {
            return $this->parseHeaders($this->lastProxyHeaders);
        }
        return [];
    }

    /**
     * @param $url
     * @param array $options
     * @param array $headers
     * @param bool $array
     * @param bool $permanent
     * @param bool $force
     *
     * @return mixed
     */
    protected function getFromProxy($url, array $options = [], array $headers = [],
                                    $array = false, $permanent = false,
                                    $force = false
    )
    {
        $requestUrl = ConnectorTrait::bindUrlOptions($url, $options);
        $key = [
            'url' => $requestUrl,
        ];

        $now = new \DateTime();
        $storedCall = $this->proxy->find($key);
        if (null === $storedCall || $force) {
            $requestHeaders = $this->buildHeaders($requestUrl, $headers);
            $response = $this->getAbsolute($url, $options, $headers, false, false);
            if ($response) {
                $data = [
                    'data' => $response,
                    'url' => $url,
                    'headers' => $this->getLastHeaders(),
                    'timestamp' => $now->getTimestamp(),
                    'ttl' => $permanent ? null : 86400,
                    'permanent' => $permanent,
                    'request_headers' => $requestHeaders
                ];
                $this->proxy->save($key, $data);
            }
            return json_decode(json_encode($response), $array);
        }

        if ($this->recordHasExpired($storedCall)) {
            $this->proxy->delete($key);
            return $this->getFromProxy($url, $options, $requestHeaders, $array, $permanent, $force);
        }
        $this->lastProxyHeaders = json_decode(json_encode($storedCall->headers), true);
        return json_decode(json_encode($storedCall->data), $array);
    }

    /**
     * @param \stdClass $record
     *
     * @return bool
     */
    protected function recordHasExpired(\stdClass $record)
    {
        $now = new \DateTime();
        $expirationTime = $record->timestamp + $record->ttl;
        if ($record->permanent || $now->getTimestamp() < $expirationTime) {
            return false;
        }
        return true;
    }
}