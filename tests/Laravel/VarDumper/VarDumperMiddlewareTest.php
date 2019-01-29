<?php

namespace Tests\AvtoDev\DevTools\Laravel\VarDumper;

use Illuminate\Routing\Router;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Contracts\Foundation\Application;
use AvtoDev\DevTools\Laravel\VarDumper\ServiceProvider;
use AvtoDev\DevTools\Laravel\VarDumper\VarDumperMiddleware;
use AvtoDev\DevTools\Tests\PHPUnit\Traits\CreatesApplicationTrait;

class VarDumperMiddlewareTest extends \Illuminate\Foundation\Testing\TestCase
{
    use CreatesApplicationTrait;

    const MAGIC_GLOBAL_VARIABLE = 'DEV_DUMP_NON_CLI';

    const CACHE_DIR             = __DIR__ . '/../../temp';

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $_SERVER[static::MAGIC_GLOBAL_VARIABLE] = true;
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        unset($_SERVER[static::MAGIC_GLOBAL_VARIABLE]);

        (new Filesystem)->cleanDirectory(static::CACHE_DIR);

        parent::tearDown();
    }

    /**
     * @return void
     */
    public function testDumpWithMiddlewareWorking()
    {
        /** @var Router $router */
        $router         = $this->app->make(Router::class);
        $repeat_counter = 0;

        // Two requests will contains dump data, any another - not
        $router
            ->get($path = '/test' . \random_int(1, 255), function () use (&$repeat_counter) {
                if ($repeat_counter < 2) {
                    \dev\dump('foo bar', 'john doe');

                    $repeat_counter++;
                }

                return \response('<html><body>bar baz</body></html>');
            })->middleware(VarDumperMiddleware::class);

        $response1 = $this->get($path);
        $response2 = $this->get($path);
        $response3 = $this->get($path);

        foreach (['foo bar', 'john doe', 'bar baz', 'window.Sfdump'] as $item) {
            $this->assertContains($item, $response1->getContent());
            $this->assertContains($item, $response2->getContent());
        }

        foreach (['foo bar', 'john doe', 'window.Sfdump'] as $item) {
            $this->assertNotContains($item, $response3->getContent());
        }
        $this->assertContains('bar baz', $response3->getContent());
    }

    /**
     * @return void
     */
    public function testDdWithException()
    {
        /** @var Router $router */
        $router = $this->app->make(Router::class);

        // \dev\dd() should work with middleware
        $router
            ->get($path_with_middleware = '/test' . \random_int(1, 255), function () {
                \dev\dd('foo bar');

                return \response('bar baz');
            })->middleware(VarDumperMiddleware::class);

        // and without it
        $router
            ->get($path_without_middleware = '/test' . \random_int(256, 512), function () {
                \dev\dd('foo bar');

                return \response('bar baz');
            });

        $response_with_middleware    = $this->get($path_with_middleware);
        $response_without_middleware = $this->get($path_without_middleware);

        foreach ([$response_with_middleware, $response_without_middleware] as $response) {
            $this->assertContains('foo bar', $response->getContent());
            $this->assertContains('window.Sfdump', $response->getContent());
            $this->assertNotContains('bar baz', $response->getContent());
        }
    }

    /**
     * @return void
     */
    protected function afterApplicationBootstrapped(Application $app)
    {
        $app['config']['cache.stores.file.path'] = static::CACHE_DIR;
        $app['config']['cache.default']          = 'file';

        $app->register(ServiceProvider::class);
    }
}
