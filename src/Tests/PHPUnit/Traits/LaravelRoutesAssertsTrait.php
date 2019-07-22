<?php

declare(strict_types = 1);

namespace AvtoDev\DevTools\Tests\PHPUnit\Traits;

use Illuminate\Routing\Route;
use Illuminate\Routing\Router;
use PHPUnit\Framework\AssertionFailedError;

/**
 * @mixin \Illuminate\Foundation\Testing\TestCase
 */
trait LaravelRoutesAssertsTrait
{
    /**
     * Assert that all existed routes point to real controllers and actions.
     *
     * @param Router $router
     *
     * @throws AssertionFailedError
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     *
     * @return void
     */
    public function assertAllRoutesHasActions(?Router $router = null): void
    {
        if ($router === null) {
            $router = $this->app->make(Router::class);
        }

        /** @var Route $route */
        foreach ($router->getRoutes() as $route) {
            $controller = $route->getAction()['uses'] ?? null;
            if (\is_string($controller)) {
                $class_method = \explode('@', $controller, 2);
                $this->assertClassExists($class_method[0]);
                $this->assertHasMethods($class_method[0], $class_method[1] ?? '__invoke');
            }
        }
    }
}
