<?php

namespace AvtoDev\DevTools\Tests\PHPUnit\Traits;

use Closure;
use Illuminate\Foundation\Application;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

trait CreatesApplicationTrait
{
    /**
     * Creates the application.
     *
     * @param Closure|null $before_bootstrap Call `$app->useStoragePath(...)` and others here
     * @param Closure|null $after_bootstrap  Register your service-providers `$app->register(...)`, etc
     *
     * @throws FileNotFoundException
     *
     * @return Application
     */
    public function createApplication(Closure $before_bootstrap = null, Closure $after_bootstrap = null)
    {
        $bootstrap_paths = [
            __DIR__ . '/../../../../../../../bootstrap/app.php',
            __DIR__ . '/../../../../vendor/laravel/laravel/bootstrap/app.php',
        ];

        foreach ($bootstrap_paths as $path) {
            if (\file_exists($path)) {
                /** @var Application $app */
                $app = require $path;

                if ($before_bootstrap instanceof Closure) {
                    $before_bootstrap->__invoke($app);
                }

                $app->make(Kernel::class)->bootstrap();

                if ($after_bootstrap instanceof Closure) {
                    $after_bootstrap->__invoke($app);
                }

                return $app;
            }
        }

        throw new FileNotFoundException('Application bootstrap file was not found');
    }
}
