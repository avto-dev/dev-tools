<?php

declare(strict_types = 1);

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
     * @return array<string>
     */
    protected function setUpTraits(): array
    {
        $uses = parent::setUpTraits();

        if (
            isset($uses[Traits\WithDatabaseQueriesLogging::class])
            && \method_exists($this, 'enableDatabaseQueriesLogging')
        ) {
            $this->enableDatabaseQueriesLogging();
        }

        if (
            isset($uses[Traits\WithDatabaseDisconnects::class])
            && \method_exists($this, 'enableDatabaseDisconnects')
        ) {
            $this->enableDatabaseDisconnects();
        }

        if (
            isset($uses[Traits\WithMemoryClean::class])
            && \method_exists($this, 'enableCleanMemory')
        ) {
            $this->enableCleanMemory();
        }

        if (
            isset($uses[Traits\WithGuzzleMocking::class])
            && \method_exists($this, 'enableGuzzleMocking')
        ) {
            $this->enableGuzzleMocking();
        }

        return $uses;
    }

    /**
     * Make some before application bootstrapped (call `$app->useStoragePath(...)`, `$app->loadEnvironmentFrom(...)`,
     * etc).
     *
     * @param Application $app
     *
     * @return void
     *
     * @see \AvtoDev\DevTools\Tests\PHPUnit\Traits\CreatesApplicationTrait::createApplication
     */
    protected function beforeApplicationBootstrapped(Application $app): void
    {
        //
    }

    /**
     * Make some after application bootstrapped (register your service-providers `$app->register(...)`, etc).
     *
     * @param Application $app
     *
     * @return void
     *
     * @see \AvtoDev\DevTools\Tests\PHPUnit\Traits\CreatesApplicationTrait::createApplication
     */
    protected function afterApplicationBootstrapped(Application $app): void
    {
        //
    }

    /**
     * Override application Guzzle HTTP client factory.
     *
     * @param UrlsMockHandler $handler
     *
     * @return void
     *
     * @see \AvtoDev\DevTools\Tests\PHPUnit\Traits\WithGuzzleMocking::enableGuzzleMocking
     */
    protected function overrideGuzzleClientBinding(UrlsMockHandler $handler): void
    {
        //$this->app->bind('your-http-client', function (Application $app) use ($handler): \GuzzleHttp\Client {
        //    return new \GuzzleHttp\Client([
        //        'handler' => \GuzzleHttp\HandlerStack::create($handler),
        //    ]);
        //});
    }
}
