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
     * @param $path
     * @param array $options
     * @param bool $array
     * @param bool $permanent
     * @param bool $force
     *
     * @return mixed
     */
    protected function getFromProxy($path, array $options = [], $array = false,
                                    $permanent = false, $force = false)
    {
        $url = $this->buildUrl($path, $options);
        $headers = $this->buildHeaders($url);
        $key = [
            'url' => $url,
            'headers' => $headers
        ];

        $now = new \DateTime();
        $storedCall = $this->proxy->find($key);
        if (null === $storedCall || $force) {
            $response = $this->get($path, $options, false, false);
            if ($response) {
                $data = [
                    'data' => $response,
                    'url' => $url,
                    'headers' => $this->getLastHeaders(),
                    'timestamp' => $now->getTimestamp(),
                    'ttl' => $permanent ? null : 86400,
                    'permanent' => $permanent
                ];
                $this->proxy->save($key, $data);
            }
            return json_decode(json_encode($response), $array);
        }

        if ($this->recordHasExpired($storedCall)) {
            $this->proxy->delete($key);
            return $this->getFromProxy($path, $options, $array, $permanent, $force);
        }
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