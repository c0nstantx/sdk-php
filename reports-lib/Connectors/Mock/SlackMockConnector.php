<?php
/**
 * This file is part of Rocketgraph service
 * <http://www.rocketgraph.com>
 */

namespace RAM\Connectors\Mock;
use RAM\Connectors\SlackConnector;
use RG\Traits\MockConnectorTrait;

/**
 * Description of SlackMockConnector
 *
 * @author K.Christofilos <kostas.christofilos@rocketgraph.com>
 */
class SlackMockConnector extends SlackConnector
{
    use MockConnectorTrait;
}