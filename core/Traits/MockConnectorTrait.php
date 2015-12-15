<?php

/**
 * This file is part of Rocketgraph service
 * <http://www.rocketgraph.com>.
 */

namespace RG\Traits;

/**
 * Description of MockConnectorTrait.
 *
 * @author K.Christofilos <kostas.christofilos@rocketgraph.com>
 */
trait MockConnectorTrait
{
    protected $responses;

    public function setResponses($responses)
    {
        $this->responses = $responses;
    }

    /**
     * Returns a mocked response
     *
     * @param string $path
     * @param array $options
     *
     * @return mixed
     */
    public function get($path, array $options = [], array $headers = [],
                        $array = false, $useProxy = true, $permanent = false,
                        $force = false
    )
    {
        $path = ConnectorTrait::sanitizePath($path);
        $query = http_build_query($options);
        if ($query !== '') {
            $path .= "?$query";
        }
        if (isset($this->responses[$path])) {
            return $this->responses[$path];
        }
        $response = new \stdClass();
        $response->status = 'error';
        $response->message = "No mock route '$path' found in app/config/responses.yml";

        return $response;
    }

}
