<?php

declare(strict_types = 1);

namespace Tests\AvtoDev\DevTools\Tests\PHPUnit\Traits;

use Tests\AvtoDev\DevTools\AbstractTestCase;
use PHPUnit\Framework\ExpectationFailedException;
use AvtoDev\DevTools\Tests\PHPUnit\AbstractLaravelTestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use AvtoDev\DevTools\Tests\PHPUnit\Traits\CreatesApplicationTrait;
use AvtoDev\DevTools\Tests\PHPUnit\Traits\InstancesAccessorsTrait;

/**
 * @covers \AvtoDev\DevTools\Tests\PHPUnit\Traits\InstancesAccessorsTrait<extended>
 */
class InstancesAccessorsTraitTest extends AbstractTestCase
{
    use InstancesAccessorsTrait;

    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws \ReflectionException
     *
     * @return void
     */
    public function testsTraitAsserts(): void
    {
        $instance             = new class {
            private $property = 'foo';

            private function method()
            {
                return 'bar';
            }
        };

        $this->assertEquals('foo', $this->getProperty($instance, 'property'));
        $this->assertEquals('bar', $this->callMethod($instance, 'method'));
    }

    /**
     * @return void
     */
    public function testGetClosureHash(): void
    {
        $test_cases = [
            function () {
            },
            function () {
                return new class extends AbstractLaravelTestCase {
                    use CreatesApplicationTrait;
                };
            },
            function () {
                $this->resetCount();
            },
        ];

        $this->assertNotEmpty($test_cases);

        foreach ($test_cases as $test_case) {
            $hash = $this->getClosureHash($test_case);

            $this->assertGreaterThanOrEqual(8, \mb_strlen($hash));

            // Second call for a closure
            $this->assertEquals($hash, $this->getClosureHash($test_case));
        }
    }
}
