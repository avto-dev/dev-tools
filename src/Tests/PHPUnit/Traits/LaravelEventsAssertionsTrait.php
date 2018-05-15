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
     * Get listeners for abstract event.
     *
     * @see https://laravel.com/docs/5.6/events
     * @see https://laravel.com/docs/5.5/events
     *
     * @param mixed|string $event_abstract
     *
     * @throws ReflectionException
     *
     * @return array
     */
    public static function getEventListenersClasses($event_abstract)
    {
        $result = [];

        foreach (\Illuminate\Support\Facades\Event::getListeners($event_abstract) as $listener_closure) {
            $reflection = new ReflectionFunction($listener_closure);
            $uses       = $reflection->getStaticVariables();

            if (isset($uses['listener'])) {
                $result[] = $uses['listener'];
            }
        }

        return $result;
    }

    /**
     * Assert that abstract listener (by class name as usual) has a listener (by class name).
     *
     * @see https://laravel.com/docs/5.6/events
     * @see https://laravel.com/docs/5.5/events
     *
     * @param string|object|mixed $event_abstract
     * @param string              $listener_class
     *
     * @throws ExpectationFailedException
     * @throws ReflectionException
     * @throws InvalidArgumentException
     */
    public static function assertEventHasListener($event_abstract, $listener_class)
    {
        if (\is_object($listener_class)) {
            $listener_class = \get_class($listener_class);
        }

        static::assertContains(
            $listener_class,
            static::getEventListenersClasses($event_abstract),
            sprintf('"%s" has no listener class "%s"', $event_abstract, $listener_class)
        );
    }

    /**
     * Assert that abstract listener (by class name as usual) has no a listener (by class name).
     *
     * @see https://laravel.com/docs/5.6/events
     * @see https://laravel.com/docs/5.5/events
     *
     * @param string|object|mixed $event_abstract
     * @param string              $listener_class
     *
     * @throws ExpectationFailedException
     * @throws ReflectionException
     * @throws InvalidArgumentException
     */
    public static function assertEventHasNoListener($event_abstract, $listener_class)
    {
        if (\is_object($listener_class)) {
            $listener_class = \get_class($listener_class);
        }

        static::assertNotContains(
            $listener_class,
            static::getEventListenersClasses($event_abstract),
            sprintf('"%s" has no listener class "%s"', $event_abstract, $listener_class)
        );
    }
}
