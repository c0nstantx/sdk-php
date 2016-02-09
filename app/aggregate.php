<?php
use Symfony\Component\HttpFoundation\Request;
use RG\Kernel;

chdir(__DIR__);
require __DIR__.'/../vendor/autoload.php';

$configPath = __DIR__.'/../app/config/';

$request = Request::createFromGlobals();
$kernel = new Kernel($configPath);
$kernel->loadContainer();

$container = $kernel->getContainer();
$reportService = $container->get('report_service');
$reportService->warmupReport();
$report = $reportService->getReport();
if (method_exists($report, 'aggregate')) {
    echo "Start aggregation...\n";
    $report->aggregate();
    echo "Aggregation finished!\n";
} else {
    echo "The report does not have an aggregation method\n";
}
