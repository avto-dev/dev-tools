<?php

declare(strict_types = 1);

namespace Tests\AvtoDev\DevTools\Tests\PHPUnit\Traits;

use Illuminate\Foundation\Application;
use Tests\AvtoDev\DevTools\AbstractTestCase;
use AvtoDev\DevTools\Tests\PHPUnit\Traits\CreatesApplicationTrait;

/**
 * @covers \AvtoDev\DevTools\Tests\PHPUnit\Traits\CreatesApplicationTrait<extended>
 */
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
    public function testTrait(): void
    {
        $this->assertFalse($this->before_called);
        $this->assertFalse($this->after_called);

        $this->assertInstanceOf(Application::class, $this->createApplication());

        $this->assertTrue($this->before_called);
        $this->assertTrue($this->after_called);
    }

    /**
     * Test bootstrap file getter.
     *
     * @return void
     */
    public function testGetApplicationBootstrapFiles(): void
    {
        $list  = (array) $this->getApplicationBootstrapFiles();
        $found = false;

        $this->assertNotEmpty($list);

        foreach ($list as $application_bootstrap_file) {
            if (\file_exists($application_bootstrap_file)) {
                $found = true;

                break;
            }
        }

        $this->assertTrue($found, 'Bootstrap file was not found');
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
