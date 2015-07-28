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
     * @param $path
     * @param array $options
     *
     * @return \Buzz\Message\MessageInterface
     */
    public function get($path, $options = array());

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
