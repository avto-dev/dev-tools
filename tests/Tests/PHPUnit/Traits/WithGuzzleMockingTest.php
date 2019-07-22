<?php

declare(strict_types = 1);

namespace Tests\AvtoDev\DevTools\Tests\PHPUnit\Traits;

use Tarampampam\GuzzleUrlMock\UrlsMockHandler;
use AvtoDev\DevTools\Tests\PHPUnit\AbstractLaravelTestCase;
use AvtoDev\DevTools\Tests\PHPUnit\Traits\WithGuzzleMocking;

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
    protected function tearDown(): void
    {
        $this->called = false;

        parent::tearDown();
    }

    /**
     * @return void
     */
    public function testHandlerCreating(): void
    {
        $this->assertInstanceOf(UrlsMockHandler::class, $this->guzzleHandler());
    }

    /**
     * @return void
     */
    public function testBingingMethodCalled(): void
    {
        $this->assertTrue($this->called);
    }

    /**
     * @return void
     */
    public function overrideGuzzleClientBinding(UrlsMockHandler $handler): void
    {
        $this->called = true;
    }
}
