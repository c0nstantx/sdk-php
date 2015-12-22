<?php
/**
 * This file is part of Rocketgraph service
 * <http://www.rocketgraph.com>
 */

namespace RAM\Connectors\Mock;
use RAM\Connectors\GmailConnector;
use RG\Traits\MockConnectorTrait;

/**
 * Description of GmailMockConnector
 *
 * @author K.Christofilos <kostas.christofilos@rocketgraph.com>
 */
class GmailMockConnector extends GmailConnector
{
    use MockConnectorTrait;
}