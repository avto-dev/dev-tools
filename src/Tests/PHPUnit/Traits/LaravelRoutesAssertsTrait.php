<?php

declare(strict_types = 1);

namespace AvtoDev\DevTools\Tests\PHPUnit\Traits;

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
            $controller = $route->getAction()['uses'] ?? null;
            if (\is_string($controller)) {
                $class_method = \explode('@', $controller, 2);

                static::assertClassExists($class_method[0]);
                static::assertHasMethods($class_method[0], $class_method[1] ?? '__invoke');
            }
        }
    }
}
