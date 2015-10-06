<?php
/**
 * This file is part of Rocketgraph service
 * <http://www.rocketgraph.com>
 */

namespace RAM\Connectors;
use Buzz\Browser;

/**
 * Description of NoAuthConnector
 *
 * @author K.Christofilos <kostas.christofilos@rocketgraph.com>
 */
class NoAuthConnector
{
    public function __construct(Browser $httpClient)
    {
        $this->client = $httpClient;
        $this->userAgent = 'Rocketgraph-engine';
    }

    /**
     * Get result from API
     *
     * @param string $url
     * @param array $options
     * @return mixed
     */
    public function get($url, $options = array())
    {
        $query = http_build_query($options);
        if ($query !== '') {
            $url .= "?$query";
        }
        $headers = ['User-Agent' => $this->userAgent];
        $response = $this->client->get($url, $headers);

        return json_decode($response->getContent());
    }

    public function getName()
    {
        return 'open';
    }
}