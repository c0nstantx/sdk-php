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
        $this->report = null;
        foreach($dirIterator as $dir) {
            if (!$dir->isDot() && $dir->isFile()) {
                $reportPath = $dir->getPathname();
                if ($this->isValidReport($reportPath)) {
                    include_once $reportPath;
                    $class = $this->getReportClass($reportPath);
                    $this->report = new $class(
                        $this->container->get('event_dispatcher'),
                        $this->container->get('storage_service')
                    );
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

        $openConnector = $this->container->get('connector_service')->buildOpenConnector();
        $this->report->setOpenConnector($openConnector);
        $this->report->setSentiment(new Sentiment());
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
            && ($tokenized->getBaseClass() === 'RAM\BaseReport'
            || $tokenized->getBaseClass() === '\RAM\BaseReport');
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

            if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') {
                $this->copyFolder($reportPublic, $symlinkFolder);
            } else {
                @symlink($reportPublic, $symlinkFolder);
            }
        }
    }

    /**
     * Copy recursively a folder to another
     *
     * @param string $source
     * @param string $destination
     */
    protected function copyFolder($source, $destination)
    {
        if (!file_exists($destination)) {
            mkdir($destination, 0777, true);
        }
        $directory = new \DirectoryIterator($source);
        foreach($directory as $dir) {
            if (!$dir->isDot()) {
                $asset = $dir->getPathname();
                if (is_dir($asset)) {
                    $this->copyFolder($asset, $destination.DIRECTORY_SEPARATOR.basename($asset));
                } else {
                    $this->copyFile($asset, $destination);
                }
            }
        }
    }

    /**
     * Copy a file to another folder
     *
     * @param string $file
     * @param string $destinationFolder
     */
    protected function copyFile($file, $destinationFolder)
    {
        $destFile = $destinationFolder.DIRECTORY_SEPARATOR.basename($file);
        if (!file_exists($destFile)) {
            copy($file, $destFile);
        }
    }
}