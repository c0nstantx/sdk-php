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
     * Returns a registered extension or null if not exist
     *
     * @param string $extension
     * @return null|\Twig_Extension
     */
    public function findExtension($extension)
    {
        if (isset($this->extensions[$extension])) {
            return $this->extensions[$extension];
        }
        return null;
    }

    /**
     * Find and registers all available extensions.
     */
    protected function findExtensions()
    {
        /* TODO: Automate extension addition */
        $routingExtension = new ReportRouting($this->templatingHelper, $this->report);
        $this->extensions[$routingExtension->getName()] = $routingExtension;
    }
}
