<?php
/**
 * This file is part of Rocketgraph service
 * <http://www.rocketgraph.com>
 */

namespace RAM\Connectors\Mock;
use RAM\Connectors\StripeConnector;
use RG\Traits\MockConnectorTrait;

/**
 * Description of StripeMockConnector
 *
 * @author K.Christofilos <kostas.christofilos@rocketgraph.com>
 */
class StripeMockConnector extends StripeConnector
{
    use MockConnectorTrait;
}