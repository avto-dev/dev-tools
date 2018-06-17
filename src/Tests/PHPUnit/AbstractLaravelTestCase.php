<?php

declare(strict_types=1);

namespace AvtoDev\DevTools\Tests\PHPUnit;

use AvtoDev\DevTools\Tests\PHPUnit\Traits\WithDatabaseQueriesLogging;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\TestCase;

abstract class AbstractLaravelTestCase extends TestCase
{
    use Traits\AdditionalAssertionsTrait,
        Traits\InstancesAccessorsTrait,
        Traits\CreatesApplicationTrait,
        Traits\LaravelLogFilesAssertsTrait,
        Traits\LaravelEventsAssertionsTrait,
        Traits\LaravelCommandsAssertionsTrait;

    /**
     * {@inheritdoc}
     */
    protected function setUpTraits()
    {
        $uses = parent::setUpTraits();

        if (isset($uses[WithDatabaseQueriesLogging::class])) {
            $this->enableDatabaseQueriesLogging();
        }

        return $uses;
    }

    /**
     * Make some before application bootstrapped (call `$app->useStoragePath(...)`, `$app->loadEnvironmentFrom(...)`,
     * etc).
     *
     * @see \AvtoDev\DevTools\Tests\PHPUnit\Traits\CreatesApplicationTrait::createApplication
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
     * @return void
     */
    protected function afterApplicationBootstrapped(Application $app)
    {
        //
    }
}
