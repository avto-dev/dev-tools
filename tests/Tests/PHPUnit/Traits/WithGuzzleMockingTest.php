<?php

declare(strict_types = 1);

namespace Tests\AvtoDev\DevTools\Tests\PHPUnit\Traits;

use AvtoDev\DevTools\Tests\PHPUnit\Traits\WithGuzzleMocking;
use AvtoDev\DevTools\Tests\PHPUnit\AbstractLaravelTestCase;
use Tarampampam\GuzzleUrlMock\UrlsMockHandler;

class WithGuzzleMockingTest extends AbstractLaravelTestCase
{
    use WithGuzzleMocking;

    /**
     * @var bool
     */
    protected $called = false;

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        $this->called = false;

        parent::tearDown();
    }

    /**
     * @return void
     */
    public function testHandlerCreating()
    {
        $this->assertInstanceOf(UrlsMockHandler::class, $this->guzzleHandler());
    }

    /**
     * @return void
     */
    public function testBingingMethodCalled()
    {
        $this->assertTrue($this->called);
    }

    /**
     * @return void
     */
    public function overrideGuzzleClientBinding(UrlsMockHandler $handler)
    {
        $this->called = true;
    }
}
