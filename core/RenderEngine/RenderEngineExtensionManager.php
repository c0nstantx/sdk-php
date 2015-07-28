<?php

/**
 * This file is part of Rocketgraph service
 * <http://www.rocketgraph.com>.
 */

namespace RG\RenderEngine;

use RAM\BaseReport;

/**
 * Description of RenderEngineExtensionManager.
 *
 * @author K.Christofilos <kostas.christofilos@rocketgraph.com>
 */
class RenderEngineExtensionManager
{
    protected $report;

    protected $templatingHelper;

    protected $extensions = array();

    public function __construct(ReportTemplatingHelper $templatingHelper,
                                BaseReport $report)
    {
        $this->templatingHelper = $templatingHelper;
        $this->report = $report;
        $this->findExtensions();
    }

    /**
     * @return array
     */
    public function getExtensions()
    {
        return $this->extensions;
    }

    /**
     * Find and registers all available extensions.
     */
    protected function findExtensions()
    {
        $routingExtension = new ReportRouting($this->templatingHelper, $this->report);
        $this->extensions[] = $routingExtension;
    }
}
