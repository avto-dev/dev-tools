<?php

namespace Tests\AvtoDev\DevTools\Laravel\VarDumper;

use AvtoDev\DevTools\Laravel\VarDumper\DumpStack;
use AvtoDev\DevTools\Laravel\VarDumper\VarDumperMiddleware;
use Illuminate\Contracts\Http\Kernel as HttpKernel;
use Illuminate\Contracts\Foundation\Application;
use AvtoDev\DevTools\Tests\PHPUnit\Traits\CreatesApplicationTrait;
use AvtoDev\DevTools\Laravel\VarDumper\ServiceProvider;

class ServiceProviderTest extends \Illuminate\Foundation\Testing\TestCase
{
    use CreatesApplicationTrait;

    /**
     * @return void
     */
    public function testMiddlewareIsRegistered()
    {
        $this->assertTrue($this->app->make(HttpKernel::class)->hasMiddleware(VarDumperMiddleware::class));
    }

    /**
     * @return void
     */
    public function testServiceContainers()
    {
        $this->assertInstanceOf(DumpStack::class, $this->app->make(DumpStack::class));
    }

    /**
     * @return void
     */
    protected function afterApplicationBootstrapped(Application $app)
    {
        $app->register(ServiceProvider::class);
    }
}
