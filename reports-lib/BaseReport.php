<?php

/**
 * This file is part of Rocketgraph service
 * <http://www.rocketgraph.com>.
 *
 * Report As Module (RAM)
 * This is the Base report specification class.
 */
namespace RAM;

use Doctrine\Common\Collections\ArrayCollection;
use RG\Interfaces\ReportInterface;
use RG\Exception\ConnectorNotFoundException;
use RG\Storage;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use RG\Event\FilterReportEvent;
use RG\Event\ReportEvents;

/**
 * Description of ReportBase.
 *
 * @author K.Christofilos <kostas.christofilos@rocketgraph.com>
 */
abstract class BaseReport implements ReportInterface
{
    /**
     * Rendered response from report.
     *
     * @var string
     */
    protected $response = '';

    /* @var \Symfony\Component\EventDispatcher\EventDispatcherInterface */
    protected $dispatcher;

    /* @var \Twig_Environment */
    protected $renderEngine;

    /* @var array */
    protected $styles = array();

    /* @var array */
    protected $scripts = array();

    /* @var ArrayCollection */
    protected $connectors;

    /* @var array */
    protected $params;

    /* @var \RG\Storage */
    protected $storage;

    /**
     * @param EventDispatcherInterface $dispatcher
     */
    final public function __construct(EventDispatcherInterface $dispatcher, Storage $storage)
    {
        $this->dispatcher = $dispatcher;
        $this->storage = $storage;
    }

    /**
     * @param $connectors
     * @return $this
     */
    final public function setConnectors($connectors)
    {
        $this->connectors = $connectors;

        return $this;
    }

    /**
     * @return array
     */
    final public function getStyles()
    {
        return $this->styles;
    }

    /**
     * @return array
     */
    final public function getScripts()
    {
        return $this->scripts;
    }

    /**
     * @param string $css
     * @return $this
     */
    final public function addStyle($css)
    {
        $this->styles[] = $css;

        return $this;
    }

    /**
     * @param string $script
     * @return $this
     */
    final public function addScript($script)
    {
        $this->scripts[] = $script;

        return $this;
    }

    /**
     * Set input params
     *
     * @param array $params
     *
     * @return $this
     */
    final public function setParams($params = [])
    {
        $this->params = $params;

        return $this;
    }

    /**
     * Get a specific input parameters
     *
     * @param string $param
     *
     * @return null|string
     */
    final public function getParam($param)
    {
        if (isset($this->params[$param])) {
            return $this->params[$param];
        }

        return null;
    }

    /**
     * Get all input params
     *
     * @return array
     */
    final public function getParams()
    {
        return $this->params;
    }

    /**
     * @param \Twig_Environment $engine
     * @return $this
     */
    final public function setRenderEngine(\Twig_Environment $engine)
    {
        $this->renderEngine = $engine;

        return $this;
    }

    /**
     * Resets a report.
     */
    final public function reset()
    {
        $this->uninstall();
        $this->install();
    }

    /**
     * Register the report.
     */
    final public function register()
    {
        $preEvent = new FilterReportEvent($this);
        $this->dispatcher->dispatch(ReportEvents::REPORT_PRE_INSTALL, $preEvent);
        $this->install();
        $postEvent = new FilterReportEvent($this);
        $this->dispatcher->dispatch(ReportEvents::REPORT_POST_INSTALL, $postEvent);
    }

    /**
     * Unregister the report.
     */
    final public function unregister()
    {
        $preEvent = new FilterReportEvent($this);
        $this->dispatcher->dispatch(ReportEvents::REPORT_PRE_UNINSTALL, $preEvent);
        $this->uninstall();
        $postEvent = new FilterReportEvent($this);
        $this->dispatcher->dispatch(ReportEvents::REPORT_POST_UNINSTALL, $postEvent);
    }

    /**
     * Run the report and return the response.
     *
     * @return string
     */
    final public function run()
    {
        $preEvent = new FilterReportEvent($this);
        $this->dispatcher->dispatch(ReportEvents::REPORT_PRE_RENDER, $preEvent);
        $response = $this->render();
        $this->response = $this->renderEngine->transformAssetPaths($response);
        $postEvent = new FilterReportEvent($this);
        $this->dispatcher->dispatch(ReportEvents::REPORT_POST_RENDER, $postEvent);

        return $this->response;
    }

    /**
     * @param string $response
     */
    final public function setResponse($response)
    {
        $this->response = $response;
    }

    /**
     * @return string
     */
    final public function getResponse()
    {
        return $this->response;
    }

    /**
     * Returns report's path.
     *
     * @return string
     */
    final public function getReportPath()
    {
        $reflectionClass = new \ReflectionClass($this);

        return dirname($reflectionClass->getFileName());
    }

    /**
     * {@inheritdoc}
     */
    final public function getConnector($provider)
    {
        foreach($this->connectors as $connectorName => $connector) {
            if ($connectorName === $provider) {
                return $connector;
            }
        }
        throw new ConnectorNotFoundException($provider);
    }

    /**
     * {@inheritdoc}
     */
    public function install() {}

    /**
     * {@inheritdoc}
     */
    public function uninstall() {}

    /**
     * {@inheritdoc}
     */
    abstract public function render();
}
