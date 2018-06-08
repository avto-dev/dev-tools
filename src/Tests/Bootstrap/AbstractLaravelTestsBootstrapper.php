<?php

declare(strict_types = 1);

namespace AvtoDev\DevTools\Tests\Bootstrap;

use Exception;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Application;
use Symfony\Component\Console\Output\ConsoleOutput;

/**
 * Class AbstractLaravelTestsBootstrapper.
 */
abstract class AbstractLaravelTestsBootstrapper extends AbstractTestsBootstrapper
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * @var Filesystem
     */
    protected $files;

    /**
     * AbstractLaravelTestsBootstrapper constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->app   = $this->createApplication();
        $this->files = $this->app->make('files');

        parent::__construct();

        $this->log(null);
    }

    /**
     * Creates the application.
     *
     * @return Application
     */
    abstract public function createApplication();

    /**
     * Show "styled" console message.
     *
     * @param string|null $message
     * @param string      $style
     */
    protected function log($message = null, $style = 'info')
    {
        /** @var ConsoleOutput|null $output */
        static $output = null;

        if (! ($output instanceof ConsoleOutput)) {
            $output = $this->app->make(ConsoleOutput::class);
        }

        $output->writeln(empty((string) $message)
            ? ''
            : sprintf('<%1$s>> Bootstrap:</%1$s> <%2$s>%3$s</%2$s>', 'comment', $style, $message)
        );
    }
}
