<?php

namespace AvtoDev\DevTools\Tests\PHPUnit\Traits;

use ReflectionFunction;
use ReflectionException;
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

/**
 * Trait LaravelEventsAssertionsTrait.
 *
 * @mixin \Illuminate\Foundation\Testing\TestCase
 */
trait LaravelEventsAssertionsTrait
{
    /**
     * Assert that abstract listener (by class name as usual) has a listener (by class name).
     *
     * @see https://laravel.com/docs/5.6/events
     * @see https://laravel.com/docs/5.5/events
     *
     * @param mixed|string $event_abstract
     * @param string       $listener_class
     *
     * @throws ExpectationFailedException
     * @throws ReflectionException
     * @throws InvalidArgumentException
     */
    public static function assertEventHasListenerClass($event_abstract, $listener_class)
    {
        $has = false;

        foreach (\Illuminate\Support\Facades\Event::getListeners($event_abstract) as $listener_closure) {
            $reflection = new ReflectionFunction($listener_closure);
            $uses       = $reflection->getStaticVariables();

            if (isset($uses['listener']) && $uses['listener'] === $listener_class) {
                $has = true;

                break;
            }
        }

        static::assertTrue($has, sprintf('"%s" has no listener class "%s"', $event_abstract, $listener_class));
    }
}
