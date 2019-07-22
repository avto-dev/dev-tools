<?php

declare(strict_types = 1);

namespace Tests\AvtoDev\DevTools\Laravel\DatabaseQueriesLogger;

use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Contracts\Foundation\Application;
use AvtoDev\DevTools\Tests\PHPUnit\Traits\CreatesApplicationTrait;
use AvtoDev\DevTools\Laravel\DatabaseQueriesLogger\ServiceProvider;
use AvtoDev\DevTools\Tests\PHPUnit\Traits\LaravelEventsAssertionsTrait;
use AvtoDev\DevTools\Laravel\DatabaseQueriesLogger\QueryExecutedEventsListener;

class ServiceProviderTest extends \Illuminate\Foundation\Testing\TestCase
{
    use CreatesApplicationTrait,
        LaravelEventsAssertionsTrait;

    /**
     * Test event listener subscribed.
     *
     * @return void
     */
    public function testListenerRegistered(): void
    {
        $this->assertEventHasListener(QueryExecuted::class, QueryExecutedEventsListener::class);
    }

    /**
     * @return void
     */
    protected function afterApplicationBootstrapped(Application $app)
    {
        $app->register(ServiceProvider::class);
    }
}
