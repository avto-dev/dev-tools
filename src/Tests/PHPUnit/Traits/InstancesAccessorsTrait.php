<?php

namespace AvtoDev\DevTools\Tests\PHPUnit\Traits;

use ReflectionClass;
use ReflectionException;

/**
 * Trait InstancesAccessorsTrait.
 */
trait InstancesAccessorsTrait
{
    /**
     * Calls a instance method (public/private/protected) by its name.
     *
     * @param object $object
     * @param string $method_name
     * @param array  $args
     *
     * @throws ReflectionException
     *
     * @return mixed
     */
    public static function callMethod($object, $method_name, array $args = [])
    {
        $class  = new ReflectionClass($object);
        $method = $class->getMethod($method_name);

        $method->setAccessible(true);

        return $method->invokeArgs($object, $args);
    }

    /**
     * Get a instance property (public/private/protected) value.
     *
     * @param object $object
     * @param string $property_name
     *
     * @throws ReflectionException
     *
     * @return mixed
     */
    public static function getProperty($object, $property_name)
    {
        $reflection = new ReflectionClass($object);

        $property = $reflection->getProperty($property_name);
        $property->setAccessible(true);

        return $property->getValue($object);
    }
}
