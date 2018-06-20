<?php

namespace Tests\AvtoDev\DevTools\Tests\PHPUnit\Traits;

use Illuminate\Database\Connection;
use Illuminate\Config\Repository as ConfigRepository;
use AvtoDev\DevTools\Tests\PHPUnit\AbstractLaravelTestCase;
use AvtoDev\DevTools\Tests\PHPUnit\Traits\WithDatabaseDisconnects;

class WithDatabaseDisconnectsTest extends AbstractLaravelTestCase
{
    use WithDatabaseDisconnects;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        /** @var ConfigRepository $config */
        $config = $this->app->make('config');

        $config->set('database.default', 'sqlite');
        $config->set('database.connections.sqlite', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        $this->app->make('db')->reconnect();
    }

    /**
     * Test `disconnectFromAllDatabaseConnections()` method.
     *
     * @return void
     */
    public function testDisconnectFromAllDatabaseConnections()
    {
        $this->assertTrue(
            $this->app->make('db')->connection()->unprepared(
                $sql = 'SELECT name FROM sqlite_master WHERE type = "table"')
        );

        $this->assertTrue($this->databaseHasActiveConnections());
        $this->assertTrue($this->disconnectFromAllDatabaseConnections($this->app));
        $this->assertFalse($this->databaseHasActiveConnections());
    }

    /**
     * Test `disconnectFromAllDatabaseConnections()` method without passing application instance.
     *
     * @return void
     */
    public function testDisconnectFromAllDatabaseConnectionsWithoutPassingApp()
    {
        $this->assertTrue($this->databaseHasActiveConnections());
        $this->assertTrue($this->disconnectFromAllDatabaseConnections());
        $this->assertFalse($this->databaseHasActiveConnections());
    }

    /**
     * Test closure registration.
     *
     * @return void
     */
    public function testClosureRegistration()
    {
        $closure_hash = static::getClosureHash($this->databaseDisconnectsClosureFactory());
        $found        = false;

        foreach ($this->beforeApplicationDestroyedCallbacks as $callback) {
            if (static::getClosureHash($callback) === $closure_hash) {
                $found = true;

                break;
            }
        }

        $this->assertTrue($found, 'Closure is not registered on application destroyed');
    }

    /**
     * Database has an active connection?
     *
     * @return bool
     */
    protected function databaseHasActiveConnections(): bool
    {
        foreach ($this->app->make('db')->getConnections() as $connection) {
            if ($connection instanceof Connection && $connection->getPdo() instanceof \PDO) {
                return true;
            }
        }

        return false;
    }
}
