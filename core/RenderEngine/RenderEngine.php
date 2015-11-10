<?php
/**
 * This file is part of Rocketgraph service
 * <http://www.rocketgraph.com>
 */

namespace RG\RenderEngine;
use RAM\BaseReport;
use RG\Exception\ExtensionNotLoadedException;

/**
 * Description of RenderEngine
 *
 * @author K.Christofilos <kostas.christofilos@rocketgraph.com>
 */
class RenderEngine extends \Twig_Environment
{
    protected $report;

    protected $baseReportView;

    protected $templatingHelper;

    public function __construct(\Twig_LoaderInterface $loader,
                                BaseReport $report,
                                ReportTemplatingHelper $templatingHelper,
                                $options = array())
    {
        parent::__construct($loader, $options);
        $this->report = $report;
        $this->extensionManager = new RenderEngineExtensionManager(
            $templatingHelper, $report);
        $this->templatingHelper = $templatingHelper;
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
     * Transform asset paths based on the report
     *
     * @param $content
     * @return string
     * @throws ExtensionNotLoadedException
     */
    public function transformAssetPaths($content)
    {
        $doc = new \DOMDocument();
        if (!$content) {
            return '';
        }
        $doc->loadHTML($content);
        $reportRouting = $this->extensionManager->findExtension('report_routing');

        if (null === $reportRouting) {
            throw new ExtensionNotLoadedException('report_routing');
        }

        /* Transform images */
        $images = $doc->getElementsByTagName('img');
        foreach($images as $image) {
            $src = $image->getAttribute('src');
            $src = $this->sanitizeAsset($src);
            if ($this->assetNeedsTransform($src)) {
                $src = $reportRouting->getPath($src);
            }
            $image->setAttribute('src', $src);
        }

        /* Transform styles */
        $styles = $doc->getElementsByTagName('link');
        foreach($styles as $style) {
            $href = $style->getAttribute('href');
            $href = $this->sanitizeAsset($href);
            if ($this->assetNeedsTransform($href)) {
                $href = $reportRouting->getPath($href);
            }
            $style->setAttribute('href', $href);
        }

        /* Transform scripts */
        $scripts = $doc->getElementsByTagName('script');
        foreach($scripts as $script) {
            $src = $script->getAttribute('src');
            $src = $this->sanitizeAsset($src);
            if ($this->assetNeedsTransform($src)) {
                $src = $reportRouting->getPath($src);
            }
            $script->setAttribute('src', $src);
        }
        return $doc->saveHTML();
    }

    /**
     * @param string $asset
     *
     * @return string
     */
    protected function sanitizeAsset($asset)
    {
        return str_replace('\\', '/', $asset);
    }

    /**
     * Returns if aseet path needs transformation
     * @param string $asset
     * @return bool
     */
    protected function assetNeedsTransform($asset)
    {
        return !filter_var($asset, FILTER_VALIDATE_URL) && !$this->assetExist($asset);
    }

    /**
     * Returns if asset exists
     *
     * @param string $asset
     * @return bool
     */
    protected function assetExist($asset)
    {
        if (substr($asset, 0, 1) !== '/') {
            $asset = '/'.$asset;
        }
        return file_exists($this->templatingHelper->getWebPath().$asset);
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
