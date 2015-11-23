<?php

/**
 * This file is part of Rocketgraph service
 * <http://www.rocketgraph.com>.
 */

namespace RG\RenderEngine;

use RAM\BaseReport;

/**
 * Description of ReportRouting.
 *
 * @author K.Christofilos <kostas.christofilos@rocketgraph.com>
 */
class ReportRouting extends \Twig_Extension
{
    protected $templatingHelper;

    protected $report;

    public function __construct(ReportTemplatingHelper $templatingHelper,
                                BaseReport $report)
    {
        $this->templatingHelper = $templatingHelper;
        $this->report = $report;
    }

    /**
     * @return BaseReport
     */
    public function getReport()
    {
        return $this->report;
    }

    /**
     * Get extension name
     *
     * @return string
     */
    public function getName()
    {
        return 'report_routing';
    }

    /**
     * List of extension functions
     *
     * @return array
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('url', array($this, 'getUrl')),
            new \Twig_SimpleFunction('path', array($this, 'getPath')),
        );
    }

    /**
     * Get asset path
     *
     * @param $name
     * @param array $parameters
     * @param bool|false $relative
     *
     * @return string
     */
    public function getPath($name, $parameters = array(), $relative = false)
    {
        if ($relative === true) {
            return $this->getUrl($name, $parameters);
        }

        return $this->templatingHelper->getReportAssetPath($this->report)
        .DIRECTORY_SEPARATOR.$name;
    }

    /**
     * Get asset url
     *
     * @param string $name
     * @param array $parameters
     *
     * @return string
     */
    public function getUrl($name, $parameters = array())
    {
        return $this->getPath($name, $parameters);
    }
}
