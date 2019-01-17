<?php

declare(strict_types = 1);

namespace AvtoDev\DevTools\Tests\PHPUnit\Traits;

use InvalidArgumentException;
use Illuminate\Routing\Route;
use Illuminate\Routing\Router;

trait LaravelRoutesAssertsTrait
{
    /**
     * Assert that all existed routes point to real controllers and actions.
     *
     * @param Router $router
     *
     * @throws \InvalidArgumentException
     */
    public function assertAllRoutesHasActions($router = null)
    {
        if ($router === null) {
            $router = $this->app->make(Router::class);
        }

        if ($router instanceof Router) {
            /** @var Route $route */
            foreach ($router->getRoutes() as $route) {
                $controller = $route->getAction()['uses'] ?? null;
                if (\is_string($controller)) {
                    $class_method = \explode('@', $controller, 2);
                    static::assertClassExists($class_method[0]);
                    static::assertHasMethods($class_method[0], $class_method[1] ?? '__invoke');
                }
            }
        } else {
            throw new InvalidArgumentException('Router must be instance of ' . Router::class);
        }
    }
}
