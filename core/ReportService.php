<?php
/**
 * This file is part of Rocketgraph service
 * <http://www.rocketgraph.com>
 */

namespace RG;
use RAM\BaseReport;
use RG\Exception\ReportNotFoundException;
use RG\RenderEngine\RenderEngine;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Request;

/**
 * Description of ReportService
 *
 * @author K.Christofilos <kostas.christofilos@rocketgraph.com>
 */
class ReportService
{
    protected $srcPath;

    protected $container;

    protected $report;

    public function __construct(Container $container)
    {
        $this->srcPath = $container->getParameter('src_path');
        $this->container = $container;
    }

    /**
     * Warmup report before render
     */
    public function warmupReport()
    {
        $dirIterator = new \DirectoryIterator($this->srcPath);
        $report = null;
        foreach($dirIterator as $dir) {
            if (!$dir->isDot() && $dir->isFile()) {
                $reportPath = $dir->getPathname();
                if ($this->isValidReport($reportPath)) {
                    include_once $reportPath;
                    $class = $this->getReportClass($reportPath);
                    $this->report = new $class($this->container->get('event_dispatcher'));
                    $this->setTemplateEngine($this->report);
                    $this->linkFolders($class);
                    break;
                }
            }
        }
        if (null === $this->report) {
            throw new ReportNotFoundException('No valid report found in src folder ('.$this->srcPath.')');
        }
        $this->setupConnectors();
        $this->setupParameters();
    }

    /**
     * Get report
     *
     * @return BaseReport|null
     */
    public function getReport()
    {
        return $this->report;
    }

    /**
     * Setup request parameters
     */
    protected function setupParameters()
    {
        $request = Request::createFromGlobals();
        $this->report->setParams($request->query->all());
    }

    /**
     * Setup report connectors
     */
    protected function setupConnectors()
    {
        $connectorService = $this->container->get('connector_service');
        $connectors = $connectorService->getConnectors();
        $this->report->setConnectors($connectors);
    }

    /**
     * Get class name from report file
     *
     * @param string $reportPath
     * @return mixed
     */
    protected function getReportClass($reportPath)
    {
        $tokenizedReport = new Tokenizer($reportPath);
        return $tokenizedReport->getClass();
    }

    /**
     * Returns if report is a valid module
     *
     * @param string $fullClass
     * @param string $className
     * @return bool
     */
    protected function isValidReport($classPath)
    {
        $tokenized = new Tokenizer($classPath);
        return
            $tokenized->isExtended()
            && $tokenized->getBaseClass() === 'RAM\BaseReport';
    }

    /**
     * Setup template engine.
     *
     * @param BaseReport $report
     */
    protected function setTemplateEngine(BaseReport $report)
    {
        $basePath = $report->getReportPath();
        $templatePaths = [
            $basePath,
            $this->container->getParameter('app_path').'/resources/views'
        ];
        $loader = new \Twig_Loader_Filesystem($templatePaths);
        $templatingHelper = $this->container->get('report_templating_helper');
        $engine = new RenderEngine($loader, $report, $templatingHelper);
        $report->setRenderEngine($engine);
    }

    /**
     * Creates symbolic links for report assets.
     *
     * @param string     $className
     */
    protected function linkFolders($className)
    {
        $reportPublic = $this->srcPath
            .DIRECTORY_SEPARATOR.'public';

        if (is_dir($reportPublic)) {
            $assetsFolder = $this->srcPath
                .DIRECTORY_SEPARATOR.'..'
                .DIRECTORY_SEPARATOR.'web'
                .DIRECTORY_SEPARATOR.'assets';

            if (!is_writable($assetsFolder)) {
                throw new \RuntimeException("Folder '$assetsFolder' is not writable. Set the proper permissions.");
            }
            $symlinkFolder = $assetsFolder.DIRECTORY_SEPARATOR.$className;

            @symlink($reportPublic, $symlinkFolder);
        }
    }
}