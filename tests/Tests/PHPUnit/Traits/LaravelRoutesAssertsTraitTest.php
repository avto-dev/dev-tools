<?php

declare(strict_types = 1);

namespace Tests\Unit\Tests\AvtoDev\DevTools\Tests\PHPUnit\Traits;

use Illuminate\Routing\Router;
use PHPUnit\Framework\ExpectationFailedException;
use AvtoDev\DevTools\Tests\PHPUnit\Traits\CreatesApplicationTrait;
use AvtoDev\DevTools\Tests\PHPUnit\Traits\AdditionalAssertionsTrait;
use AvtoDev\DevTools\Tests\PHPUnit\Traits\LaravelRoutesAssertsTrait;
use Tests\AvtoDev\DevTools\Tests\PHPUnit\Traits\Stubs\ControllerStub;

/**
 * @coversDefaultClass \AvtoDev\DevTools\Tests\PHPUnit\Traits\LaravelRoutesAssertsTrait
 */
class LaravelRoutesAssertsTraitTest extends \Illuminate\Foundation\Testing\TestCase
{
    use CreatesApplicationTrait, AdditionalAssertionsTrait, LaravelRoutesAssertsTrait;

    /**
     * @var Router
     */
    protected $router;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->router = $this->app->make(Router::class);
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        unset($this->router);
        parent::tearDown();
    }

    /**
     * Test assertion.
     *
     * @covers ::assertAllRoutesHasActions
     *
     * @throws \InvalidArgumentException
     */
    public function testExistedRoute()
    {
        $this->router->get('example', ControllerStub::class . '@testAction');

        $this->assertAllRoutesHasActions();
    }

    /**
     * Test route with using method.
     *
     * @covers ::assertAllRoutesHasActions
     *
     * @throws \InvalidArgumentException
     */
    public function testInvokedRoute()
    {
        $this->router->get('example', ControllerStub::class);

        $this->assertAllRoutesHasActions();
    }

    /**
     * Test non existing method in controller.
     *
     * @covers ::assertAllRoutesHasActions
     *
     * @throws \InvalidArgumentException
     */
    public function testNotExistedMethod()
    {
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessageRegExp('~Has no method named~');
        $this->router->get('example', ControllerStub::class . '@nonExistsAction');

        $this->assertAllRoutesHasActions();
    }

    /**
     * Test non existing controller class.
     *
     * @covers ::assertAllRoutesHasActions
     *
     * @throws \InvalidArgumentException
     */
    public function testNotExistedClass()
    {
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessageRegExp('~Class .* was not found~');

        $this->router->get('example', 'SomeClassThatNotExists@testAction');

        $this->assertAllRoutesHasActions();
    }
}
