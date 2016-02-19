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
    /** @var BaseReport  */
    protected $report;

    /** @var ReportTemplatingHelper  */
    protected $templatingHelper;

    /** @var string */
    protected $wrapperUrl;

    /** @var array  */
    protected $extensions = array();

    public function __construct(ReportTemplatingHelper $templatingHelper,
                                BaseReport $report, $wrapperUrl)
    {
        $this->templatingHelper = $templatingHelper;
        $this->report = $report;
        $this->wrapperUrl = $wrapperUrl;
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
        $routingExtension = new ReportRouting($this->templatingHelper, $this->report, $this->wrapperUrl);
        $this->extensions[$routingExtension->getName()] = $routingExtension;
    }
}
