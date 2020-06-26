<?php

declare(strict_types = 1);

namespace AvtoDev\DevTools\Tests\PHPUnit\Traits;

use Closure;
use Exception;
use ReflectionClass;
use ReflectionException;
use SuperClosure\Serializer as ClosureSerializer;

/**
 * @deprecated Will be removed in future releases
 */
trait InstancesAccessorsTrait
{
    /**
     * Calls a instance method (public/private/protected) by its name.
     *
     * @param object       $object
     * @param string       $method_name
     * @param array<mixed> $args
     *
     * @throws ReflectionException
     *
     * @return mixed
     *
     * @deprecated
     */
    public function callMethod($object, string $method_name, array $args = [])
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
     *
     * @deprecated https://github.com/sebastianbergmann/phpunit/issues/3338
     */
    public function getProperty($object, string $property_name)
    {
        return $this->getObjectAttribute($object, $property_name);
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
     *
     * @deprecated
     */
    public function getClosureHash(Closure $closure): string
    {
        // @codeCoverageIgnoreStart
        if (! class_exists(ClosureSerializer::class)) {
            throw new Exception(\sprintf(
                'Package [%s] is required for [%s] method',
                'jeremeamia/superclosure',
                __METHOD__
            ));
        }

        // @codeCoverageIgnoreEnd

        return sha1(
            (new ClosureSerializer)->serialize($closure->bindTo(new \stdClass))
        );
    }
}
