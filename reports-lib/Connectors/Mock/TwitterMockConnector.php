<?php
/**
 * This file is part of Rocketgraph service
 * <http://www.rocketgraph.com>
 */

namespace RAM\Connectors\Mock;
use RAM\Connectors\TwitterConnector;
use RG\Traits\MockConnectorTrait;

/**
 * Description of TwitterMockConnector
 *
 * @author K.Christofilos <kostas.christofilos@rocketgraph.com>
 */
class TwitterMockConnector extends TwitterConnector
{
    use MockConnectorTrait;
}