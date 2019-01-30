<?php

namespace Tests\AvtoDev\DevTools\Laravel\VarDumper;

use Illuminate\Contracts\Foundation\Application;
use AvtoDev\DevTools\Laravel\VarDumper\DumpStack;
use Illuminate\Contracts\Http\Kernel as HttpKernel;
use AvtoDev\DevTools\Laravel\VarDumper\ServiceProvider;
use AvtoDev\DevTools\Laravel\VarDumper\VarDumperMiddleware;
use AvtoDev\DevTools\Tests\PHPUnit\Traits\CreatesApplicationTrait;

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
