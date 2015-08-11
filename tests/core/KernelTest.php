<?php
/**
 * This file is part of Rocketgraph service
 * <http://www.rocketgraph.com>
 */

namespace Tests\Core;
use RG\Kernel;
use Symfony\Component\HttpFoundation\Request;

/**
 * Description of KernelTest
 *
 * @author K.Christofilos <kostas.christofilos@rocketgraph.com>
 */
class KernelTest extends \PHPUnit_Framework_TestCase
{
    public function testHandle()
    {
        $kernel = new Kernel(__DIR__.'/../fixtures/config_sandbox');
        $kernel->loadContainer();
        $container = $kernel->getContainer();
        $container->setParameter('src_path', __DIR__.'/../fixtures/reports/report1');
        $request = new Request();
        $response = $kernel->handle($request);
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $response);
    }

    public function testParseConfig()
    {
        $kernel = new Kernel(__DIR__.'/../fixtures/config_sandbox');
        $kernel->loadContainer();
        $container = $kernel->getContainer();
        $container->setParameter('src_path', __DIR__.'/../fixtures/reports/report1');
        $request = new Request();
        $response = $kernel->handle($request);
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $response);
    }
}