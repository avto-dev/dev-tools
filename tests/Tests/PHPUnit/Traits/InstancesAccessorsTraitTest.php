<?php

namespace Tests\AvtoDev\DevTools\Tests\PHPUnit\Traits;

use AvtoDev\DevTools\Tests\PHPUnit\Traits\InstancesAccessorsTrait;
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Tests\AvtoDev\DevTools\AbstractTestCase;

class InstancesAccessorsTraitTest extends AbstractTestCase
{
    use InstancesAccessorsTrait;

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws \ReflectionException
     */
    public function testsTraitAsserts()
    {
        $instance = new class
        {
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
