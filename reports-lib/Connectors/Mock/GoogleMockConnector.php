<?php
/**
 * This file is part of Rocketgraph service
 * <http://www.rocketgraph.com>
 */

namespace RAM\Connectors\Mock;
use RAM\Connectors\GoogleConnector;
use RG\Traits\MockConnectorTrait;

/**
 * Description of GoogleMockConnector
 *
 * @author K.Christofilos <kostas.christofilos@rocketgraph.com>
 */
class GoogleMockConnector extends GoogleConnector
{
    use MockConnectorTrait;
}