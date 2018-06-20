<?php

namespace Tests\AvtoDev\DevTools\Tests\PHPUnit\Traits;

use Tests\AvtoDev\DevTools\AbstractTestCase;
use PHPUnit\Framework\ExpectationFailedException;
use AvtoDev\DevTools\Tests\PHPUnit\AbstractLaravelTestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use AvtoDev\DevTools\Tests\PHPUnit\Traits\InstancesAccessorsTrait;

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
    public function testsTraitAsserts()
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
    public function testGetClosureHash()
    {
        $test_cases = [
            function () {},
            function () {
                return new class extends AbstractLaravelTestCase {

                };
            },
            function () {
                return $this->resetCount();
            },
        ];

        $this->assertNotEmpty($test_cases);

        foreach ($test_cases as $test_case) {
            $hash = $this->getClosureHash($test_case);

            $this->assertGreaterThanOrEqual(8, \strlen($hash));
        }
    }
}
