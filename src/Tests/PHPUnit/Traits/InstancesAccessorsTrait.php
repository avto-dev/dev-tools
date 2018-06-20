<?php

declare(strict_types=1);

namespace AvtoDev\DevTools\Tests\PHPUnit\Traits;

use Closure;
use Exception;
use ReflectionClass;
use ReflectionException;
use SuperClosure\Serializer as ClosureSerializer;

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
    public static function callMethod($object, string $method_name, array $args = [])
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
    public static function getProperty($object, string $property_name)
    {
        $reflection = new ReflectionClass($object);

        $property = $reflection->getProperty($property_name);
        $property->setAccessible(true);

        return $property->getValue($object);
    }

    /**
     * Calculate closure hash sum.
     *
     * As you know - you cannot serialize closure 'as is' for hashing. So - this is a little hack for this shit!
     *
     * @param Closure $closure
     *
     * @throws Exception
     *
     * @return string
     */
    public static function getClosureHash(Closure $closure): string
    {
        // @codeCoverageIgnoreStart
        if (! class_exists(ClosureSerializer::class)) {
            throw new Exception(sprintf('Package [%s] is required for [%s] method', 'jeremeamia/superclosure', __METHOD__));
        }
        // @codeCoverageIgnoreEnd

        return sha1(
            (new ClosureSerializer())->serialize($closure->bindTo(new \stdClass))
        );
    }
}
