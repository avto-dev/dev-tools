<?php

namespace AvtoDev\DevTools\Tests\PHPUnit;

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\TestCase;

/**
 * Class AbstractIlluminateTestCase.
 *
 * Abstract Illuminate test case.
 */
abstract class AbstractIlluminateTestCase extends TestCase
{
    use Traits\AdditionalAssertionsTrait,
        Traits\InstancesAccessorsTrait,
        Traits\CreatesApplicationTrait,
        Traits\LaravelEventsAssertionsTrait;

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
