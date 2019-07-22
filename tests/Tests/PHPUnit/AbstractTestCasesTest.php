<?php

declare(strict_types = 1);

namespace Tests\AvtoDev\DevTools\Tests\PHPUnit;

use Tests\AvtoDev\DevTools\AbstractTestCase;
use AvtoDev\DevTools\Tests\PHPUnit\Traits\CarbonAssertionsTrait;
use AvtoDev\DevTools\Tests\PHPUnit\Traits\CreatesApplicationTrait;
use AvtoDev\DevTools\Tests\PHPUnit\Traits\InstancesAccessorsTrait;
use AvtoDev\DevTools\Tests\PHPUnit\Traits\AdditionalAssertionsTrait;
use AvtoDev\DevTools\Tests\PHPUnit\Traits\LaravelLogFilesAssertsTrait;
use AvtoDev\DevTools\Tests\PHPUnit\Traits\LaravelEventsAssertionsTrait;
use AvtoDev\DevTools\Tests\PHPUnit\Traits\LaravelCommandsAssertionsTrait;

/**
 * @covers \AvtoDev\DevTools\Tests\PHPUnit\AbstractTestCase<extended>
 * @covers \AvtoDev\DevTools\Tests\PHPUnit\AbstractLaravelTestCase<extended>
 */
class AbstractTestCasesTest extends AbstractTestCase
{
    use AdditionalAssertionsTrait;

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testAbstractTestCase(): void
    {
        $instance = new class extends \AvtoDev\DevTools\Tests\PHPUnit\AbstractTestCase {
        };

        $this->assertInstanceOf(\PHPUnit\Framework\TestCase::class, $instance);

        $this->assertClassUsesTraits($instance, [
            AdditionalAssertionsTrait::class,
            InstancesAccessorsTrait::class,
            CarbonAssertionsTrait::class,
        ]);
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testAbstractLaravelTestCase(): void
    {
        $instance = new class extends \AvtoDev\DevTools\Tests\PHPUnit\AbstractLaravelTestCase {
            use CreatesApplicationTrait;
        };

        $this->assertInstanceOf(\PHPUnit\Framework\TestCase::class, $instance);
        $this->assertInstanceOf(\Illuminate\Foundation\Testing\TestCase::class, $instance);

        $this->assertClassUsesTraits($instance, [
            AdditionalAssertionsTrait::class,
            InstancesAccessorsTrait::class,
            CreatesApplicationTrait::class,
            LaravelEventsAssertionsTrait::class,
            LaravelLogFilesAssertsTrait::class,
            LaravelCommandsAssertionsTrait::class,
            CarbonAssertionsTrait::class,
        ]);
    }
}
