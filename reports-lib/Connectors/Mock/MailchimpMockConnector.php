<?php
/**
 * This file is part of Rocketgraph service
 * <http://www.rocketgraph.com>
 */

namespace RAM\Connectors\Mock;
use RAM\Connectors\MailchimpConnector;
use RG\Traits\MockConnectorTrait;

/**
 * Description of MailchimpMockConnector
 *
 * @author K.Christofilos <kostas.christofilos@rocketgraph.com>
 */
class MailchimpMockConnector extends MailchimpConnector
{
    use MockConnectorTrait;
}