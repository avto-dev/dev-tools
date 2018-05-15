<?php

namespace Tests\AvtoDev\DevTools\Tests\PHPUnit;

use Tests\AvtoDev\DevTools\AbstractTestCase;
use AvtoDev\DevTools\Tests\PHPUnit\Traits\CreatesApplicationTrait;
use AvtoDev\DevTools\Tests\PHPUnit\Traits\InstancesAccessorsTrait;
use AvtoDev\DevTools\Tests\PHPUnit\Traits\AdditionalAssertionsTrait;
use AvtoDev\DevTools\Tests\PHPUnit\Traits\LaravelEventsAssertionsTrait;

class AbstractTestCasesTest extends AbstractTestCase
{
    use AdditionalAssertionsTrait;

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testAbstractTestCase()
    {
        $instance = new class extends \AvtoDev\DevTools\Tests\PHPUnit\AbstractTestCase {
        };

        $this->assertInstanceOf(\PHPUnit\Framework\TestCase::class, $instance);

        $this->assertClassUsesTraits($instance, [
            AdditionalAssertionsTrait::class,
            InstancesAccessorsTrait::class,
        ]);
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testAbstractLaravelTestCase()
    {
        $instance = new class extends \AvtoDev\DevTools\Tests\PHPUnit\AbstractLaravelTestCase {
        };

        $this->assertInstanceOf(\PHPUnit\Framework\TestCase::class, $instance);
        $this->assertInstanceOf(\Illuminate\Foundation\Testing\TestCase::class, $instance);

        $this->assertClassUsesTraits($instance, [
            AdditionalAssertionsTrait::class,
            InstancesAccessorsTrait::class,
            CreatesApplicationTrait::class,
            LaravelEventsAssertionsTrait::class,
        ]);
    }
}
