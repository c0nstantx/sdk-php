<?php
/**
 * This file is part of Rocketgraph service
 * <http://www.rocketgraph.com>
 */

namespace RAM\Connectors\Mock;
use RAM\Connectors\NoAuthConnector;
use RG\Traits\MockConnectorTrait;

/**
 * Description of NoAuthMockConnector
 *
 * @author K.Christofilos <kostas.christofilos@rocketgraph.com>
 */
class NoAuthMockConnector extends NoAuthConnector
{
    use MockConnectorTrait;
}