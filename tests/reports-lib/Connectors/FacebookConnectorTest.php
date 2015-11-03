<?php

/**
 * This file is part of Rocketgraph service
 * <http://www.rocketgraph.com>.
 */

namespace Tests\RAM\Connectors;

use RAM\Connectors\FacebookConnector;
use RG\Proxy;

/**
 * Description of FacebookConnectorTest.
 *
 * @author K.Christofilos <kostas.christofilos@rocketgraph.com>
 */
class FacebookConnectorTest extends \PHPUnit_Framework_TestCase
{
    protected $connector;

    protected $response;

    protected $authUrl;

    public function setUp()
    {
        $browser = $this->getMockBuilder('Buzz\Browser')
            ->disableOriginalConstructor()
            ->getMock();

        $proxy = new Proxy('path');
        $this->connector = $this->getMockBuilder('RAM\Connectors\FacebookConnector')
            ->setConstructorArgs([$browser, $proxy])
            ->setMethods([
                'get',
                'getAuthorizationUrl'
            ])
            ->getMock();

        $this->connector->expects($this->any())
            ->method('get')
            ->willReturnCallback(function() {
                return $this->response;
            });

        $this->connector->expects($this->any())
            ->method('getAuthorizationUrl')
            ->willReturnCallback(function() {
                return $this->authUrl;
            });
    }

    public function testConnector()
    {
        $path = $this->connector->buildUrlFromPath('path');
        $this->assertEquals(FacebookConnector::API_HOST.'/'.FacebookConnector::DEFAULT_GRAPH_VERSION.'/path', $path);

        $this->assertEquals('', $this->connector->getDisplayName());
        $this->response = new \stdClass();
        $this->response->name = 'Name';
        $this->assertEquals('Name', $this->connector->getDisplayName());

        $this->authUrl = 'http://rocketgraph.com';
        $this->assertEquals('http://rocketgraph.com&display=popup', $this->connector->retrieveAuthUrl());

        $this->assertEquals('https://www.facebook.com/v2.2/dialog/oauth', $this->connector->getBaseAuthorizationUrl());
        $this->assertEquals('https://graph.facebook.com/v2.2/oauth/access_token', $this->connector->getBaseAccessTokenUrl([]));
    }
}
