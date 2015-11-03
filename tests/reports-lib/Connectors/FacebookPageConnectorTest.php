<?php

/**
 * This file is part of Rocketgraph service
 * <http://www.rocketgraph.com>.
 */

namespace Tests\RAM\Connectors;

use League\OAuth2\Client\Token\AccessToken;
use RG\Proxy;

/**
 * Description of FacebookPageConnectorTest.
 *
 * @author K.Christofilos <kostas.christofilos@rocketgraph.com>
 */
class FacebookPageConnectorTest extends \PHPUnit_Framework_TestCase
{
    protected $connector;

    protected $response;

    public function setUp()
    {
        $browser = $this->getMockBuilder('Buzz\Browser')
            ->disableOriginalConstructor()
            ->getMock();

        $proxy = new Proxy('path');
        $this->connector = $this->getMockBuilder('RAM\Connectors\FacebookPageConnector')
            ->setConstructorArgs([$browser, $proxy])
            ->setMethods([
                'get',
            ])
            ->getMock();

        $this->connector->expects($this->any())
            ->method('get')
            ->willReturnCallback(function() {
                return $this->response;
            });
    }

    public function testConnector()
    {
        $this->assertEquals('', $this->connector->getDisplayName());

        $params = $this->connector->getExtraParameters();
        $this->assertNull($params);

        $this->response = new \stdClass();
        $page = new \stdClass();
        $page->id = 1;
        $page->name = 'name';
        $page->access_token = 'page_token';
        $data = [$page];
        $this->response->data = $data;

        $params = $this->connector->getExtraParameters();
        $this->assertTrue(is_array($params));
        $this->assertEquals('facebook_page', $params['select']['name']);
        $value = key($params['select']['options']);

        $this->connector->setToken(new AccessToken(['access_token' => 'token']));
        $this->connector->setExtraParameters(['facebook_page'=>$value, 'unknown'=>'empty']);

        $this->assertTrue((bool)$this->connector->needsExtraParameters());
        $this->assertEquals(1, $this->connector->getPageId());
        $this->assertEquals('name', $this->connector->getPageName());
        $this->assertEquals('page_token', $this->connector->getPageToken());

        $this->assertEquals('name', $this->connector->getDisplayName());
    }
}
