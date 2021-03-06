<?php
/**
 * This file is part of Rocketgraph service
 * <http://www.rocketgraph.com>
 */

namespace RG;
use Sensio\Bundle\BuzzBundle\SensioBuzzBundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Yaml\Yaml;

/**
 * Description of Kernel
 *
 * @author K.Christofilos <kostas.christofilos@rocketgraph.com>
 */
class Kernel
{
    /**
     * @var string
     */
    protected $configPath;

    /**
     * @var ContainerBuilder
     */
    protected $container;

    protected $debug;

    /**
     * Load configuration path on kernel
     *
     * @param string $configPath
     */
    public function __construct($configPath, $debug = false)
    {
        $this->configPath = $configPath;
        $this->debug = $debug;
    }

    /**
     * Load dependency injection container
     */
    public function loadContainer()
    {
        $this->container = new ContainerBuilder();
        $this->container->setParameter('src_path', __DIR__.'/../src');
        $this->container->setParameter('app_path', __DIR__.'/../app');
        $this->container->setParameter('web_path', __DIR__.'/../web');
        $this->container->setParameter('kernel.debug', $this->debug);

        $locator = new FileLocator($this->configPath);
        $loader = new YamlFileLoader($this->container, $locator);

        /* Register Bundles */
        $this->registerBundles();

        /* Load SDK configuration */
        $loader->load('config.yml');
        $this->parseConfig();

        /* Load bundle extensions */
        $this->loadExtensions();

        $connectors = Yaml::parse($this->configPath.'/connectors.yml');

        $this->container->setParameter('connectors', $connectors['connectors']);

        /* Load services */
        $loader->load('services.yml');

        $this->registerListeners();
    }

    /**
     * Get the dependency injection container
     *
     * @return ContainerBuilder
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * Parse request and generate response
     *
     * @param Request $request
     *
     * @return Response
     */
    public function handle(Request $request)
    {
        $reportService = $this->container->get('report_service');
        $reportService->warmupReport();
        $report = $reportService->getReport();
        $response = new Response($report->run());

        return $response;
    }

    /**
     * Register listers to event dispatcher
     */
    protected function registerListeners()
    {
        $dispatcher = $this->container->get('event_dispatcher');

        $listeners = $this->getRegisteredListeners();

        foreach($listeners as $listener) {
            $dispatcher->addListenerService($listener['event'], [
                $listener['id'],
                $listener['method']
            ]);
        }
    }

    /**
     * Get registered listeners from services
     *
     * @return array
     */
    protected function getRegisteredListeners()
    {
        $serviceDefinitions = $this->container->getDefinitions();

        $listeners = [];
        foreach($serviceDefinitions as $id => $definition) {
            $tags = $definition->getTags();
            if (isset($tags['kernel.event_listener'])) {
                foreach($tags['kernel.event_listener'] as $listener) {
                    $listener['id'] = $id;
                    $listeners[] = $listener;
                }
            }
        }

        return $listeners;
    }

    /**
     * Parse configuration values
     */
    protected function parseConfig()
    {
        if ($this->container->getParameter('show_errors')) {
            error_reporting(E_ALL);
            ini_set('display_errors', '1');
        }

        if ($this->container->getParameter('connection') === 'sandbox') {
            $responses = Yaml::parse($this->configPath.'/responses.yml');
            if (is_array($responses)) {
                foreach ($responses['responses'] as $connector => &$response) {
                    foreach ($response as $path => &$values) {
                        $values = json_decode(json_encode($values));
                    }
                }
                $this->container->setParameter('responses', $responses['responses']);
                return;
            }
        }
        $this->container->setParameter('responses', []);
    }

    /**
     * Register extra bundles.
     *
     * @param ContainerBuilder $container A ContainerBuilder instance
     */
    protected function registerBundles()
    {
        $bundles = [
            new SensioBuzzBundle(),
        ];

        foreach ($bundles as $bundle) {
            if ($extension = $bundle->getContainerExtension()) {
                $this->container->registerExtension($extension);
            }
        }
        foreach ($bundles as $bundle) {
            $bundle->build($this->container);
        }
    }

    /**
     * Load registered bundles' extensions
     */
    protected function loadExtensions()
    {
        $extensions = $this->container->getExtensions();
        foreach($extensions as $extension) {
            $configs = $this->container->getExtensionConfig($extension->getAlias());
            $extension->load($configs, $this->container);
        }
    }
}