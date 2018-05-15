<?php

namespace Tests\AvtoDev\DevTools\Tests\PHPUnit\Traits;

use Tests\AvtoDev\DevTools\AbstractTestCase;
use PHPUnit\Framework\ExpectationFailedException;
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
}
