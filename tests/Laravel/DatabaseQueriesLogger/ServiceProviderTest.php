<?php

namespace Tests\AvtoDev\DevTools\Laravel\DatabaseQueriesLogger;

use Illuminate\Database\Events\QueryExecuted;
use AvtoDev\DevTools\Laravel\DatabaseQueriesLogger\QueryExecutedEventsListener;
use AvtoDev\DevTools\Laravel\DatabaseQueriesLogger\ServiceProvider;
use AvtoDev\DevTools\Tests\PHPUnit\Traits\CreatesApplicationTrait;
use AvtoDev\DevTools\Tests\PHPUnit\Traits\LaravelEventsAssertionsTrait;
use Illuminate\Contracts\Foundation\Application;

class ServiceProviderTest extends \Illuminate\Foundation\Testing\TestCase
{
    use CreatesApplicationTrait,
        LaravelEventsAssertionsTrait;

    /**
     * @return void
     */
    protected function afterApplicationBootstrapped(Application $app)
    {
        $app->register(ServiceProvider::class);
    }

    /**
     * Test event listener subscribed.
     *
     * @return void
     */
    public function testListenerRegistered()
    {
        $this->assertEventHasListener(QueryExecuted::class, QueryExecutedEventsListener::class);
    }
}
