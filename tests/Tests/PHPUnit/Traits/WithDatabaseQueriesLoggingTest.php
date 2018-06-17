<?php

namespace Tests\AvtoDev\DevTools\Tests\PHPUnit\Traits;

use Illuminate\Config\Repository as ConfigRepository;
use AvtoDev\DevTools\Tests\PHPUnit\AbstractLaravelTestCase;
use AvtoDev\DevTools\Tests\PHPUnit\Traits\WithDatabaseQueriesLogging;

class WithDatabaseQueriesLoggingTest extends AbstractLaravelTestCase
{
    use WithDatabaseQueriesLogging;

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

        $this->clearLaravelLogs();
    }

    public function testTraitWorking()
    {
        /** @var \Illuminate\Database\SQLiteConnection  $connection */
        $connection = $this->app->make('db')->connection();

        $connection->unprepared($sql = 'SELECT name FROM sqlite_master WHERE type = "table"');

        $this->assertLogFileContains($sql);
    }
}
