<?php

/**
 * This file is part of Rocketgraph service
 * <http://www.rocketgraph.com>.
 */

namespace RG\Interfaces;
use RG\Connection;

/**
 * Description of ConnectorInterface.
 *
 * @author K.Christofilos <kostas.christofilos@rocketgraph.com>
 */
interface ConnectorInterface
{
    /**
     * Get an API call response.
     *
     * @param string $path          The requested API path
     * @param array $options        Extra options for the requested path
     * @param array $headers        Custom request headers
     * @param bool $array           Return the results as an associative array
     * @param bool $useProxy        Use proxy for the results
     * @param bool $permanent       Persist permanent the call to proxy (Never update)
     * @param bool $force           Force update of proxy record for the call
     *
     * @return \Buzz\Message\MessageInterface
     */
    public function get($path, array $options = [], array $headers = [],
                        $array = false, $useProxy = true, $permanent = false,
                        $force = false);

    /**
     * Build absolute URL from path.
     *
     * @param $path
     *
     * @return string
     */
    public function buildUrlFromPath($path);

    /**
     * Connect to Provider.
     */
    public function setupProvider();

    /**
     * Build connector token, based on subscription's connection
     *
     * @param Connection $connection
     */
    public function buildToken(Connection $connection);

    /**
     * Returns the display name of connection
     *
     * @return string
     */
    public function getDisplayName();

    /**
     * Returns if connector needs extra parameters
     *
     * @return bool
     */
    public function needsExtraParameters();
}
