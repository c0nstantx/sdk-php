<?php

/**
 * This file is part of Rocketgraph service
 * <http://www.rocketgraph.com>.
 */

namespace RG\ReportsBundle\Tests\RAM;

use RG\ReportsBundle\Entity\Provider;
use RG\ReportsBundle\Entity\ReportConnector;
use RG\ReportsBundle\Event\ReportEvents;
use RG\ReportsBundle\Model\ConnectorService;
use RG\TestingBundle\Model\DatabaseTestCase;
use RG\TestingBundle\Model\StubGenerator;
use RG\TestingBundle\Traits\StubTrait;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;

/**
 * Description of BaseReportTest.
 *
 * @author K.Christofilos <kostas.christofilos@rocketgraph.com>
 */
class BaseReportTest extends \PHPUnit_Framework_TestCase
{
    protected $baseReport;

    protected $installed = false;

    protected $uninstalled = false;

    protected $content;

    public function setUp()
    {
        $dispatcher = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
            ->disableOriginalConstructor()
            ->setMethods([
                'dispatch',
                'addListener',
                'addSubscriber',
                'removeListener',
                'removeSubscriber',
                'getListeners',
                'hasListeners'
            ])
            ->getMock();

        $this->baseReport = $this->getMockBuilder('RAM\BaseReport')
            ->setConstructorArgs([$dispatcher])
            ->setMethods([
                'install',
                'uninstall',
                'render'
            ])
            ->getMockForAbstractClass();

        $this->baseReport->expects($this->any())
            ->method('install')
            ->willReturnCallback(function() {
                $this->installed = true;
            });

        $this->baseReport->expects($this->any())
            ->method('uninstall')
            ->willReturnCallback(function() {
                $this->uninstalled = true;
            });

        $this->baseReport->expects($this->any())
            ->method('render')
            ->willReturnCallback(function() {
                $this->content = 'Rendered content';
                return $this->content;
            });
    }

    public function testSettersGetters()
    {
        /* Script/Styles */
        $this->assertEquals(0, count($this->baseReport->getStyles()));
        $this->assertEquals(0, count($this->baseReport->getScripts()));

        $this->baseReport->addStyle('style1');
        $this->assertEquals(1, count($this->baseReport->getStyles()));
        $this->baseReport->addScript('script1');
        $this->assertEquals(1, count($this->baseReport->getScripts()));

        /* Connectors */
        $this->baseReport->setConnectors(['twitter'=>'connector']);
        $this->assertEquals('connector', $this->baseReport->getConnector('twitter'));

        /* Render engine */
        $engine = $this->getMockBuilder('\Twig_Environment')
            ->disableOriginalConstructor()
            ->getMock();
        $this->baseReport->setRenderEngine($engine);

        /* Response */
        $this->baseReport->setResponse('content');
        $this->assertEquals('content', $this->baseReport->getResponse());

        /* Report path */
        $path = $this->baseReport->getReportPath();

        $reflection = new \ReflectionClass($this->baseReport);
        $this->assertEquals(dirname($reflection->getFileName()), $path);
    }

    public function testFlow()
    {
        $this->assertFalse($this->installed);
        $this->baseReport->register();
        $this->assertTrue($this->installed);

        $this->assertFalse($this->uninstalled);
        $this->baseReport->unregister();
        $this->assertTrue($this->uninstalled);

        $response = $this->baseReport->run();
        $this->assertEquals($this->content, $response);

        $this->installed = false;
        $this->uninstalled = false;
        $this->baseReport->reset();

        $this->assertTrue($this->installed);
        $this->assertTrue($this->uninstalled);
    }

    public function testExceptions()
    {

        /* Exceptions */
        $this->baseReport->setConnectors(['twitter'=>'connector']);
        $this->setExpectedException('RG\Exception\ConnectorNotFoundException');
        $this->baseReport->getConnector('unknown');

    }
}
