<?php

namespace Tests\AvtoDev\DevTools\Tests\PHPUnit\Traits;

use AvtoDev\DevTools\Tests\PHPUnit\Traits\CreatesApplicationTrait;
use AvtoDev\DevTools\Tests\PHPUnit\Traits\LaravelEventsAssertionsTrait;
use Illuminate\Support\Facades\Event;

class LaravelEventsAssertionsTraitTest extends \Illuminate\Foundation\Testing\TestCase
{
    use CreatesApplicationTrait,
        LaravelEventsAssertionsTrait;

    /**
     * @return void
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \ReflectionException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testTrait()
    {
        $event    = 'foo.event';
        $listener = new class
        {
        };

        $this->assertEventHasNoListener($event, $listener);

        Event::listen($event, \get_class($listener));

        $this->assertEventHasListener($event, $listener);
    }
}
