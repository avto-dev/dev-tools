<?php

declare(strict_types = 1);

namespace AvtoDev\DevTools\Tests\PHPUnit\Traits;

use ReflectionFunction;
use ReflectionException;
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

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
    public function getEventListenersClasses($event_abstract): array
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
     * @param string|object       $listener_class
     * @param string              $message
     *
     * @throws ExpectationFailedException
     * @throws ReflectionException
     * @throws InvalidArgumentException
     *
     * @return void
     */
    public function assertEventHasListener($event_abstract, $listener_class, string $message = ''): void
    {
        if (\is_object($listener_class)) {
            $listener_class = \get_class($listener_class);
        }

        $this->assertContains(
            $listener_class,
            $this->getEventListenersClasses($event_abstract),
            $message === ''
                ? \sprintf('"%s" has no listener class "%s"', $event_abstract, $listener_class)
                : $message
        );
    }

    /**
     * Assert that abstract listener (by class name as usual) has no a listener (by class name).
     *
     * @see https://laravel.com/docs/5.6/events
     * @see https://laravel.com/docs/5.5/events
     *
     * @param string|object|mixed $event_abstract
     * @param string|object       $listener_class
     * @param string              $message
     *
     * @throws ExpectationFailedException
     * @throws ReflectionException
     * @throws InvalidArgumentException
     *
     * @return void
     */
    public function assertEventHasNoListener($event_abstract, $listener_class, string $message = ''): void
    {
        if (\is_object($listener_class)) {
            $listener_class = \get_class($listener_class);
        }

        $this->assertNotContains(
            $listener_class,
            $this->getEventListenersClasses($event_abstract),
            $message === ''
                ? \sprintf('"%s" has no listener class "%s"', $event_abstract, $listener_class)
                : $message
        );
    }
}
