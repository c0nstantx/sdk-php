<?php
/**
 * This file is part of Rocketgraph service
 * <http://www.rocketgraph.com>
 */

namespace RAM\Connectors\Mock;
use RAM\Connectors\SquareConnector;
use RG\Traits\MockConnectorTrait;

/**
 * Description of SquareMockConnector
 *
 * @author K.Christofilos <kostas.christofilos@rocketgraph.com>
 */
class SquareMockConnector extends SquareConnector
{
    use MockConnectorTrait;
}