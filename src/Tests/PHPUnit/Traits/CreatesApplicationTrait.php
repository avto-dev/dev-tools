<?php

namespace AvtoDev\DevTools\Tests\PHPUnit\Traits;

use Illuminate\Foundation\Application;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

trait CreatesApplicationTrait
{
    /**
     * Creates the application.
     *
     * @throws FileNotFoundException
     *
     * @return Application
     */
    public function createApplication()
    {
        $bootstrap_paths = [
            __DIR__ . '/../bootstrap/app.php',
            __DIR__ . '/../../../../../../../bootstrap/app.php',
            __DIR__ . '/../../../../vendor/laravel/laravel/bootstrap/app.php',
        ];

        foreach ($bootstrap_paths as $path) {
            if (\file_exists($path)) {
                /** @var Application $app */
                $app = require $path;

                if (\method_exists($this, $before_method_name = 'beforeApplicationBootstrapped')) {
                    $this->$before_method_name($app);
                }

                $app->make(Kernel::class)->bootstrap();

                if (\method_exists($this, $after_method_name = 'afterApplicationBootstrapped')) {
                    $this->$after_method_name($app);
                }

                return $app;
            }
        }

        throw new FileNotFoundException('Application bootstrap file was not found');
    }
}
