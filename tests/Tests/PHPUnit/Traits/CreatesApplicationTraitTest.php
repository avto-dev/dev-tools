<?php

namespace Tests\AvtoDev\DevTools\Tests\PHPUnit\Traits;

use Illuminate\Foundation\Application;
use Tests\AvtoDev\DevTools\AbstractTestCase;
use AvtoDev\DevTools\Tests\PHPUnit\Traits\CreatesApplicationTrait;

class CreatesApplicationTraitTest extends AbstractTestCase
{
    use CreatesApplicationTrait;

    /**
     * @var bool
     */
    protected $before_called = false;

    /**
     * @var bool
     */
    protected $after_called = false;

    /**
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     *
     * @return void
     */
    public function testTrait()
    {
        $this->assertFalse($this->before_called);
        $this->assertFalse($this->after_called);

        $this->assertInstanceOf(Application::class, $this->createApplication());

        $this->assertTrue($this->before_called);
        $this->assertTrue($this->after_called);
    }

    /**
     * @param Application $app
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    protected function beforeApplicationBootstrapped($app)
    {
        $this->assertInstanceOf(Application::class, $app);

        $this->before_called = true;
    }

    /**
     * @param Application $app
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    protected function afterApplicationBootstrapped($app)
    {
        $this->assertInstanceOf(Application::class, $app);

        $this->after_called = true;
    }
}
