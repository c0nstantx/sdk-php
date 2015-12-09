<?php
/**
 * This file is part of Rocketgraph service
 * <http://www.rocketgraph.com>
 */

namespace RAM\Connectors\Mock;
use RAM\Connectors\GithubConnector;
use RG\Traits\MockConnectorTrait;

/**
 * Description of GithubMockConnector
 *
 * @author K.Christofilos <kostas.christofilos@rocketgraph.com>
 */
class GithubMockConnector extends  GithubConnector
{
    use MockConnectorTrait;
}