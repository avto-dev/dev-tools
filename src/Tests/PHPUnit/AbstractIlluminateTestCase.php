<?php

namespace AvtoDev\DevTools\Tests\PHPUnit;

use Illuminate\Foundation\Testing\TestCase;

abstract class AbstractIlluminateTestCase extends TestCase
{
    use Traits\AdditionalAssertionsTrait,
        Traits\InstancesAccessorsTrait,
        Traits\CreatesApplicationTrait,
        Traits\LaravelEventsAssertionsTrait;
}
