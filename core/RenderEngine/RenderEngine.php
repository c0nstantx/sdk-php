<?php
/**
 * This file is part of Rocketgraph service
 * <http://www.rocketgraph.com>
 */

namespace RG\RenderEngine;

use RAM\BaseReport;
use RG\Exception\ContentNotFoundException;
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
        if (null === $content) {
            throw new ContentNotFoundException('No content was found to be rendered');
        }

        libxml_use_internal_errors(true);
        $doc = new \DOMDocument();
        $doc->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES'));

        $reportRouting = $this->extensionManager->findExtension('report_routing');

        if (null === $reportRouting) {
            throw new ExtensionNotLoadedException('report_routing');
        }

        /* Transform images */
        $images = $doc->getElementsByTagName('img');
        foreach($images as $image) {
            $this->prepareAsset($image, 'src', $reportRouting);
        }

        /* Transform styles */
        $styles = $doc->getElementsByTagName('link');
        foreach($styles as $style) {
            $this->prepareAsset($style, 'href', $reportRouting);
        }

        /* Transform scripts */
        $scripts = $doc->getElementsByTagName('script');
        foreach($scripts as $script) {
            $this->prepareAsset($script, 'src', $reportRouting);
        }

        return $doc->saveHTML();
    }

    /**
     * @param \DOMElement $element
     * @param string $attribute
     * @param ReportRouting $reportRouting
     */
    protected function prepareAsset(\DOMElement $element, $attribute,
                                    ReportRouting $reportRouting
    )
    {
        $asset = $element->getAttribute($attribute);
        if ($asset) {
            $asset = $this->sanitizeAsset($asset);
            if ($this->assetNeedsTransform($asset)) {
                $asset = $reportRouting->getPath($asset);
            }
            $element->setAttribute($attribute, $asset);
        }
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
