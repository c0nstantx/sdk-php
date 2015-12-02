<?php

/**
 * This file is part of Rocketgraph service
 * <http://www.rocketgraph.com>.
 */
namespace RG\EventListener;
use RG\Event\FilterReportEvent;
use RG\RenderEngine\ReportRouting;
use RG\RenderEngine\ReportTemplatingHelper;

/**
 * Description of PostRenderReportListener.
 *
 * @author K.Christofilos <kostas.christofilos@rocketgraph.com>
 */
class PostRenderReportListener
{
    /** @var  ReportTemplatingHelper */
    protected $templatingHelper;

    public function __construct(ReportTemplatingHelper $helper)
    {
        $this->templatingHelper = $helper;
    }

    public function onReportRender(FilterReportEvent $event)
    {
        $report = $event->getReport();
        $reportRouting = new ReportRouting($this->templatingHelper, $report);
        $this->setScripts($reportRouting);
        $this->setStyles($reportRouting);
    }

    /**
     * Setup styles.
     *
     * @param ReportRouting $reportRouting
     */
    protected function setStyles(ReportRouting $reportRouting)
    {
        $report = $reportRouting->getReport();
        $styles = $report->getStyles();

        $content = $report->getResponse();
        $doc = new \DOMDocument();
        $doc->loadHTML($content);

        $heads = $doc->getElementsByTagName('head');
        if ($heads->length === 0) {
            /* If no head is created, add it at the top of HTML */
            $head = $doc->createElement('head');
            $html = $doc->getElementsByTagName('html');
            $html->item(0)->insertBefore($head, $html->item(0)->firstChild);
        } else {
            $head = $heads->item(0);
        }
        foreach ($styles as $style) {
            $url = $reportRouting->getUrl($style);
            $element = $doc->createElement('link');
            $element->setAttribute('rel', 'stylesheet');
            $element->setAttribute('type', 'text/css');
            $element->setAttribute('href', $url );
            $element->setAttribute('media', 'screen');
            $head->appendChild($element);
        }
        $report->setResponse($doc->saveHTML());
    }

    /**
     * Setup scripts.
     *
     * @param ReportRouting $reportRouting
     */
    protected function setScripts(ReportRouting $reportRouting)
    {
        $report = $reportRouting->getReport();
        $scripts = $report->getScripts();

        $content = $report->getResponse();
        $doc = new \DOMDocument();
        $doc->loadHTML($content);

        $heads = $doc->getElementsByTagName('head');
        if ($heads->length == 0) {
            /* If no head is created, add it at the top of HTML */
            $head = $doc->createElement('head');
            $html = $doc->getElementsByTagName('html');
            $html->item(0)->insertBefore($head, $html->item(0)->firstChild);
        } else {
            $head = $heads->item(0);
        }
        foreach ($scripts as $script) {
            $url = $reportRouting->getUrl($script);
            $element = $doc->createElement('script');
            $element->setAttribute('type', 'text/javascript');
            $element->setAttribute('src', $url );
            $head->appendChild($element);
        }
        $report->setResponse($doc->saveHTML());
    }
}
