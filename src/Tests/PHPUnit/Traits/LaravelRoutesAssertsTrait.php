<?php

declare(strict_types = 1);

namespace AvtoDev\DevTools\Tests\PHPUnit\Traits;

use Illuminate\Support\Str;
use Illuminate\Routing\Route;
use Illuminate\Routing\Router;

trait LaravelRoutesAssertsTrait
{
    /**
     * Assert that all existed routes point to real controllers and actions.
     */
    public function assertRoutesActionsExist()
    {
        /** @var Router $router */
        $router = $this->app->make(Router::class);
        /** @var Route $route */
        foreach ($router->getRoutes() as $route) {
            $controller = $route->getAction('uses');

            if (\is_string($controller) && ! Str::startsWith($controller, '\Illuminate\Routing')) {
                list($class, $method) = \explode('@', $controller, 2) + ['__invoke'];
                static::assertClassExists($class);
                static::assertHasMethods($class, $method);
            }
        }
    }
}
