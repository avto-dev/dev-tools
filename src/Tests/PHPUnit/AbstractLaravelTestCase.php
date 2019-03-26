<?php

declare(strict_types=1);

namespace AvtoDev\DevTools\Tests\PHPUnit;

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\TestCase;
use Tarampampam\GuzzleUrlMock\UrlsMockHandler;

abstract class AbstractLaravelTestCase extends TestCase
{
    use Traits\AdditionalAssertionsTrait,
        Traits\InstancesAccessorsTrait,
        Traits\CreatesApplicationTrait,
        Traits\LaravelLogFilesAssertsTrait,
        Traits\LaravelEventsAssertionsTrait,
        Traits\LaravelCommandsAssertionsTrait,
        Traits\CarbonAssertionsTrait;

    /**
     * {@inheritdoc}
     */
    protected function setUpTraits()
    {
        $uses = parent::setUpTraits();

        if (isset($uses[Traits\WithDatabaseQueriesLogging::class])) {
            $this->enableDatabaseQueriesLogging();
        }

        if (isset($uses[Traits\WithDatabaseDisconnects::class])) {
            $this->enableDatabaseDisconnects();
        }

        if (isset($uses[Traits\WithMemoryClean::class])) {
            $this->enableCleanMemory();
        }

        if (isset($uses[Traits\WithGuzzleMocking::class])) {
            $this->enableGuzzleMocking();
        }

        return $uses;
    }

    /**
     * Make some before application bootstrapped (call `$app->useStoragePath(...)`, `$app->loadEnvironmentFrom(...)`,
     * etc).
     *
     * @see \AvtoDev\DevTools\Tests\PHPUnit\Traits\CreatesApplicationTrait::createApplication
     *
     * @param Application $app
     *
     * @return void
     */
    protected function beforeApplicationBootstrapped(Application $app)
    {
        //
    }

    /**
     * Make some after application bootstrapped (register your service-providers `$app->register(...)`, etc).
     *
     * @see \AvtoDev\DevTools\Tests\PHPUnit\Traits\CreatesApplicationTrait::createApplication
     *
     * @param Application $app
     *
     * @return void
     */
    protected function afterApplicationBootstrapped(Application $app)
    {
        //
    }

    /**
     * Override application Guzzle HTTP client factory.
     *
     * @see \AvtoDev\DevTools\Tests\PHPUnit\Traits\WithGuzzleMocking::enableGuzzleMocking
     *
     * @param UrlsMockHandler $handler
     *
     * @return void
     */
    protected function overrideGuzzleClientBinding(UrlsMockHandler $handler)
    {
        //$this->app->bind('your-http-client', function (Application $app) use ($handler): \GuzzleHttp\Client {
        //    return new \GuzzleHttp\Client([
        //        'handler' => \GuzzleHttp\HandlerStack::create($handler),
        //    ]);
        //});
    }
}
