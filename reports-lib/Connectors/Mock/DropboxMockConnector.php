<?php
/**
 * This file is part of Rocketgraph service
 * <http://www.rocketgraph.com>
 */

namespace RAM\Connectors\Mock;
use RAM\Connectors\DropboxConnector;
use RG\Traits\MockConnectorTrait;

/**
 * Description of DropboxMockConnector
 *
 * @author K.Christofilos <kostas.christofilos@rocketgraph.com>
 */
class DropboxMockConnector extends DropboxConnector
{
    use MockConnectorTrait;
}