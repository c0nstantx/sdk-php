<?php
/**
 * This file is part of Rocketgraph service
 * <http://www.rocketgraph.com>
 */

namespace Tests\RAM\Connectors;
use RAM\Connectors\TwitterConnector;
use RG\Proxy;
use Symfony\Component\HttpFoundation\Request;

/**
 * Description of TwitterConnectorTest
 *
 * @author K.Christofilos <kostas.christofilos@rocketgraph.com>
 */
class TwitterConnectorTest extends \PHPUnit_Framework_TestCase
{
    protected $connector;

    protected $response;

    protected $authUrl;

    public function setUp()
    {
        $browser = $this->getMockBuilder('RG\Browser')
            ->disableOriginalConstructor()
            ->getMock();

        $proxy = new Proxy('path');
        $this->connector = $this->getMockBuilder('RAM\Connectors\TwitterConnector')
            ->setConstructorArgs([$browser, $proxy])
            ->setMethods([
                'getAuthorizationUrl',
                'get'
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
        $this->assertEquals(TwitterConnector::API_HOST.'/'.TwitterConnector::API_VERSION.'/path.json', $path);

        $this->assertEquals('', $this->connector->getDisplayName());
        $response = new \stdClass();
        $response->screen_name = 'Name';

        $this->response = $response;
        $this->assertEquals('Name', $this->connector->getDisplayName());

        $this->assertEquals($response, $this->connector->get('path', ['option']));

        $this->assertEquals('https://api.twitter.com/oauth/authenticate', $this->connector->urlAuthorization());
        $this->assertEquals('https://api.twitter.com/oauth/access_token', $this->connector->urlTokenCredentials());
        $this->assertEquals('https://api.twitter.com/1.1/account/verify_credentials.json', $this->connector->urlUserDetails());
        $this->assertEquals('https://api.twitter.com/oauth/request_token', $this->connector->urlTemporaryCredentials());

        $request = new Request();
        $this->assertFalse($this->connector->isResponse($request));
        $request->query->set('oauth_token', 'token');
        $request->query->set('oauth_verifier', 'verifier');
        $this->assertTrue($this->connector->isResponse($request));
    }
}
