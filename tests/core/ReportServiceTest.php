<?php
/**
 * This file is part of Rocketgraph service
 * <http://www.rocketgraph.com>
 */

namespace Tests\Core;
use RG\ReportService;
use RG\Test\ContainerAwareTestCase;

/**
 * Description of ReportServiceTest
 *
 * @author K.Christofilos <kostas.christofilos@rocketgraph.com>
 */
class ReportServiceTest extends ContainerAwareTestCase
{
    public function testWarmupReport()
    {
        $this->container->setParameter('src_path', __DIR__.'/../fixtures/reports/report1');
        $reportService = new ReportService($this->container);
        $this->assertNull($reportService->getReport());
        $reportService->warmupReport();
        $this->assertInstanceOf('RAM\BaseReport', $reportService->getReport());

        $this->container->setParameter('src_path', __DIR__.'/../fixtures/reports/report2');
        $reportService = new ReportService($this->container);
        try {
            $reportService->warmupReport();
        } catch (\Exception $ex) {
            $this->assertNull($reportService->getReport());
            $this->assertInstanceOf('RG\Exception\ReportNotFoundException', $ex);
        }
    }

    public function testLinkFolders()
    {
        $this->container->setParameter('src_path', __DIR__.'/../fixtures/reports/notWritable/report3');
        $reportService = new ReportService($this->container);
        try {
            $reportService->warmupReport();
        } catch(\Exception $ex) {
            $this->assertInstanceOf('\RuntimeException', $ex);
        }
    }
}