<?php

declare(strict_types = 1);

namespace Tests\AvtoDev\DevTools\Tests\PHPUnit\Traits;

use AvtoDev\DevTools\Tests\PHPUnit\Traits\WithMemoryClean;
use AvtoDev\DevTools\Tests\PHPUnit\AbstractLaravelTestCase;

class WithMemoryCleanTest extends AbstractLaravelTestCase
{
    use WithMemoryClean;

    /**
     * @var mixed property for testing
     */
    protected $test_property;

    public function testSomeShit(): void
    {
        $this->test_property = 'test';
        $this->clearMemory();
        $this->assertNull($this->test_property);
    }

    /**
     * Test closure registration.
     *
     * @return void
     */
    public function testClosureRegistration(): void
    {
        $closure_hash = $this->getClosureHash($this->cleanMemoryClosureFactory());
        $found        = false;

        foreach ($this->beforeApplicationDestroyedCallbacks as $callback) {
            if ($this->getClosureHash($callback) === $closure_hash) {
                $found = true;

                break;
            }
        }

        $this->assertTrue($found, 'Closure is not registered on application destroyed');
    }
}
