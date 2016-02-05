<?php
/**
 * This file is part of Rocketgraph service
 * <http://www.rocketgraph.com>
 */

namespace RAM\Connectors\Mock;
use RAM\Connectors\WeiboConnector;
use RG\Traits\MockConnectorTrait;

/**
 * Description of WeiboMockConnector
 *
 * @author K.Christofilos <kostas.christofilos@rocketgraph.com>
 */
class WeiboMockConnector extends WeiboConnector
{
    use MockConnectorTrait;
}