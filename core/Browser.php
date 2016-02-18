<?php
/**
 * This file is part of Rocketgraph service
 * <http://www.rocketgraph.com>
 */

namespace RG;

use Buzz\Browser as BaseBrowser;
use Buzz\Client\ClientInterface;
use Buzz\Message\Factory\FactoryInterface;

/**
 * Description of Browser
 *
 * @author K.Christofilos <kostas.christofilos@rocketgraph.com>
 */
class Browser extends BaseBrowser
{
    private $client;

    public function __construct(ClientInterface $client = null, FactoryInterface $factory = null)
    {
        parent::__construct($client, $factory);
        $this->client = $this->getClient();
    }

    /**
     * @return int
     */
    public function getTimeout()
    {
        return $this->client->getTimeout();
    }

    /**
     * @param int $seconds
     *
     * @return Browser
     */
    public function setTimeout($seconds)
    {
        $this->client->setTimeout($seconds);

        return $this;
    }

    /**
     * @param int $maxRedirects
     *
     * @return Browser
     */
    public function setMaxRedirects($maxRedirects)
    {
        $this->client->setMaxRedirects($maxRedirects);

        return $this;
    }

    /**
     * @return int
     */
    public function getMaxRedirects()
    {
        return $this->client->getMaxRedirects();
    }
}