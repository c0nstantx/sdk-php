<?php
/**
 * This file is part of Rocketgraph service
 * <http://www.rocketgraph.com>
 */

namespace Tests\Core\Event;
use RG\Event\FilterReportEvent;

/**
 * Description of FilterReportEventTest
 *
 * @author K.Christofilos <kostas.christofilos@rocketgraph.com>
 */
class FilterReportEventTest extends \PHPUnit_Framework_TestCase
{
    public function testGetReport()
    {
        $report = $this->getMockBuilder('RAM\BaseReport')
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        $event = new FilterReportEvent($report);

        $this->assertEquals($report, $event->getReport());
    }
}