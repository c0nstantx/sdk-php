<?php
/**
 * This file is part of Rocketgraph service
 * <http://www.rocketgraph.com>
 */

namespace RG;

/**
 * Description of ConsoleCommand
 *
 * @author K.Christofilos <kostas.christofilos@rocketgraph.com>
 */
abstract class ConsoleCommand
{
    protected $container;

    protected $input;

    public function __construct()
    {
        chdir(__DIR__);
        require __DIR__.'/../vendor/autoload.php';

        $configPath = __DIR__.'/../app/config/';

        $kernel = new Kernel($configPath);
        $kernel->loadContainer();

        $this->container = $kernel->getContainer();
    }

    abstract public function execute();

    /**
     * @return \Symfony\Component\DependencyInjection\ContainerBuilder
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @return mixed
     */
    public function getInput()
    {
        return $this->input;
    }

    /**
     * @param mixed $input
     *
     * @return ConsoleCommand
     */
    public function setInput($input)
    {
        $this->input = $input;

        return $this;
    }

    /**
     * Prints message to console output
     *
     * @param string $message
     * @param int $repeat
     */
    public function output($message = '', $repeat = 1)
    {
        for ($i=0; $i < $repeat; $i++) {
            echo "$message\n";
        }
    }

    /**
     * Read input from console
     *
     * @param string $prompt
     *
     * @return string
     */
    public function readline($prompt)
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') {
            echo $prompt;
            return stream_get_line(STDIN, 2048, PHP_EOL);
        }

        return readline($prompt);
    }
}