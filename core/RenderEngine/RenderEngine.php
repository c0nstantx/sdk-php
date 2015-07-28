<?php
/**
 * This file is part of Rocketgraph service
 * <http://www.rocketgraph.com>
 */

namespace RG\RenderEngine;
use RAM\BaseReport;

/**
 * Description of RenderEngine
 *
 * @author K.Christofilos <kostas.christofilos@rocketgraph.com>
 */
class RenderEngine extends \Twig_Environment
{
    protected $report;

    protected $baseReportView;

    public function __construct(\Twig_LoaderInterface $loader,
                                BaseReport $report,
                                ReportTemplatingHelper $templatingHelper,
                                $options = array())
    {
        parent::__construct($loader, $options);
        $this->report = $report;
        $this->extensionManager = new RenderEngineExtensionManager(
            $templatingHelper, $report);
        $this->registerExtensions();
    }

    /**
     * {@inheritdoc}
     */
    public function render($name, array $context = array())
    {
        $reportBody = $this->loadTemplate($name)->render($context);
        $content = $this->loadTemplate('rg_report_view.html')
            ->render(['content'=>$reportBody, 'report'=>$this->report]);

        return $content;
    }

    /**
     * Registers extensions from extension manager.
     */
    protected function registerExtensions()
    {
        $extensions = $this->extensionManager->getExtensions();
        foreach ($extensions as $extension) {
            $this->addExtension($extension);
        }
    }
}
