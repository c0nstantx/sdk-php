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
}
