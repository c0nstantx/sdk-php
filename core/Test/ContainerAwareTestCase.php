<?php
/**
 * This file is part of Rocketgraph service
 * <http://www.rocketgraph.com>
 */

namespace RG\Test;
use RG\Kernel;

/**
 * Description of ContainerAwareTestCase
 *
 * @author K.Christofilos <kostas.christofilos@rocketgraph.com>
 */
class ContainerAwareTestCase extends \PHPUnit_Framework_TestCase
{
    public $container;

    public function setUp()
    {
        $configPath = __DIR__.'/../../app/config/';

        $kernel = new Kernel($configPath);
        $kernel->loadContainer();
        $this->container = $kernel->getContainer();
    }
}