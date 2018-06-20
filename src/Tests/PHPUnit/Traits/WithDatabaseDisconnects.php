<?php

declare(strict_types=1);

namespace AvtoDev\DevTools\Tests\PHPUnit\Traits;

use Closure;
use Illuminate\Database\Connection;
use Illuminate\Contracts\Foundation\Application;

/**
 * @link https://www.neontsunami.com/posts/too-many-connections-using-phpunit-for-testing-laravel-51
 */
trait WithDatabaseDisconnects
{
    /**
     * Make disconnect for all database connections.
     *
     * @param Application $app
     *
     * @return bool
     */
    public function disconnectFromAllDatabaseConnections(Application $app = null): bool
    {
        $result = false;

        /** @var Application $app */
        $app = $app instanceof Application
            ? $app
            : $this->app;

        foreach ($app->make('db')->getConnections() as $connection) {
            if ($connection instanceof Connection) {
                $connection->disconnect();

                if ($result !== true) {
                    $result = true;
                }
            }
        }

        return $result;
    }

    /**
     * Disconnects closure factory.
     *
     * @return Closure
     */
    protected function databaseDisconnectsClosureFactory(): Closure
    {
        return function () {
            $this->disconnectFromAllDatabaseConnections();
        };
    }

    /**
     * Enable disconnects from all databases before the application is destroyed for this test class.
     *
     * @return void
     */
    public function enableDatabaseDisconnects()
    {
        $this->beforeApplicationDestroyed($this->databaseDisconnectsClosureFactory());
    }
}
