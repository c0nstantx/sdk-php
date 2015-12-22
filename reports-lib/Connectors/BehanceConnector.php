<?php
/**
 * This file is part of Rocketgraph service
 * <http://www.rocketgraph.com>
 */

namespace RAM\Connectors;
use RG\Connection;

/**
 * Description of BehanceConnector
 *
 * @author K.Christofilos <kostas.christofilos@rocketgraph.com>
 */
class BehanceConnector extends NoAuthConnector
{
    const API_HOST = 'https://behance.net';
    const API_VERSION = 'v2';

    protected $clientId;

    /**
     * Build absolute URL from path.
     *
     * @param $path
     *
     * @return string
     */
    public function buildUrlFromPath($path)
    {
        return sprintf("%s/%s/%s", self::API_HOST, self::API_VERSION, $path);
    }

    /**
     * Build connector token, based on subscription's connection
     *
     * @param Connection $connection
     */
    public function buildToken(Connection $connection)
    {
        $this->clientId = $connection->getAccessToken();
    }

    /**
     * @param string $path
     * @param array $options
     *
     * @return string
     */
    protected function buildUrl($path, $options = [])
    {
        $options = array_merge($options, ['client_id' => $this->clientId]);
        return parent::buildUrl($path, $options);
    }

    public function getName()
    {
        return 'behance';
    }
}