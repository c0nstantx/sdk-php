<?php

/**
 * This file is part of Rocketgraph service
 * <http://www.rocketgraph.com>.
 */

namespace RG\RenderEngine;

use RAM\BaseReport;
use Symfony\Bundle\FrameworkBundle\Templating\Helper\AssetsHelper;
use Symfony\Component\Asset\Package;
use Symfony\Component\Asset\Packages;
use Symfony\Component\Asset\VersionStrategy\EmptyVersionStrategy;

/**
 * Description of ReportTemplatingHelper.
 *
 * @author K.Christofilos <kostas.christofilos@rocketgraph.com>
 */
class ReportTemplatingHelper
{
    protected $templatingHelper;

    protected $reportAssetsFolder;

    protected $webPath;

    /**
     * Construct report templating helper
     *
     * @param string $reportAssetsFolder
     */
    public function __construct($reportAssetsFolder, $webPath)
    {
        $package = new Package(new EmptyVersionStrategy());
        $packages = new Packages($package);
        $this->templatingHelper = new AssetsHelper($packages);
        $this->reportAssetsFolder = $reportAssetsFolder;
        $this->webPath = $webPath;
    }

    /**
     * Returns web folder path
     *
     * @return string
     */
    public function getWebPath()
    {
        return $this->webPath;
    }

    /**
     * Get report's asset path.
     *
     * @param BaseReport $report
     *
     * @return string
     */
    public function getReportAssetPath(BaseReport $report)
    {
        return $this->templatingHelper
            ->getUrl($this->reportAssetsFolder.'/'.get_class($report));
    }
}
