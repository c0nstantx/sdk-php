<?php
/**
 * This file is part of Rocketgraph service
 * <http://www.rocketgraph.com>
 */

namespace RAM\Connectors\Mock;
use RAM\Connectors\PaypalConnector;
use RG\Traits\MockConnectorTrait;

/**
 * Description of PaypalMockConnector
 *
 * @author K.Christofilos <kostas.christofilos@rocketgraph.com>
 */
class PaypalMockConnector extends PaypalConnector
{
    use MockConnectorTrait;
}