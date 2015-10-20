<?php

/**
 * This file is part of Rocketgraph service
 * <http://www.rocketgraph.com>.
 */

namespace Tests\RAM\Connectors;

use RAM\Connectors\StripeConnector;

/**
 * Description of StripeConnectorTest.
 *
 * @author K.Christofilos <kostas.christofilos@rocketgraph.com>
 */
class StripeConnectorTest extends \PHPUnit_Framework_TestCase
{
    protected $connector;

    protected $response;

    protected $authUrl;

    public function setUp()
    {
        $browser = $this->getMockBuilder('Buzz\Browser')
            ->disableOriginalConstructor()
            ->setMethods([
                'get'
            ])
            ->getMock();

        $response = $this->getMockBuilder('Guzzle\Http\Message\Response')
            ->disableOriginalConstructor()
            ->setMethods([
                'getContent'
            ])
            ->getMock();
        $response->expects($this->any())
            ->method('getContent')
            ->willReturnCallback(function() {
                return $this->response;
            });

        $browser->expects($this->any())
            ->method('get')
            ->willReturn($response);

        $this->connector = $this->getMockBuilder('RAM\Connectors\StripeConnector')
            ->setConstructorArgs([$browser])
            ->setMethods([
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
        $this->assertEquals(StripeConnector::API_HOST.'/'.StripeConnector::API_VERSION.'/path', $path);

        $this->assertEquals('', $this->connector->getDisplayName());
        $response = new \stdClass();
        $response->display_name = 'Name';

        $this->response = json_encode($response);
        $this->assertEquals('Name', $this->connector->getDisplayName());

        $this->assertEquals($response, $this->connector->get('path', ['option']));
        $this->authUrl = 'http://rocketgraph.com';
        $this->assertEquals('http://rocketgraph.com&display=popup', $this->connector->retrieveAuthUrl());

        $this->assertEquals('https://connect.stripe.com/oauth/authorize', $this->connector->getBaseAuthorizationUrl());
        $this->assertEquals('https://connect.stripe.com/oauth/token', $this->connector->getBaseAccessTokenUrl([]));
    }
}
