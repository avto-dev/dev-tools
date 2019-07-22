<?php

declare(strict_types = 1);

namespace Tests\AvtoDev\DevTools\Tests\PHPUnit\Traits;

use Illuminate\Support\Facades\Event;
use AvtoDev\DevTools\Tests\PHPUnit\Traits\CreatesApplicationTrait;
use AvtoDev\DevTools\Tests\PHPUnit\Traits\LaravelEventsAssertionsTrait;

/**
 * @covers \AvtoDev\DevTools\Tests\PHPUnit\Traits\LaravelEventsAssertionsTrait<extended>
 */
class LaravelEventsAssertionsTraitTest extends \Illuminate\Foundation\Testing\TestCase
{
    use CreatesApplicationTrait,
        LaravelEventsAssertionsTrait;

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \ReflectionException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     *
     * @return void
     */
    public function testTrait(): void
    {
        $event    = 'foo.event';
        $listener = new class {
        };

        $this->assertEventHasNoListener($event, $listener);

        Event::listen($event, \get_class($listener));

        $this->assertEventHasListener($event, $listener);
    }
}
