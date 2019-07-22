<?php

declare(strict_types = 1);

namespace AvtoDev\DevTools\Tests\PHPUnit\Traits;

use Closure;
use PHPUnit\Framework\TestCase as PHPHUnitTestCase;
use Illuminate\Foundation\Testing\TestCase as IlluminateTestCase;

/**
 * If you want to use this trait without laravel-framework you mast call `clearMemory` method on `tearDown` in test.
 */
trait WithMemoryClean
{
    /**
     * Reset all nonstatic properties to null.
     *
     * WARNING: this code has bad influence on time of execution
     */
    public function clearMemory(): void
    {
        $refection = new \ReflectionObject($this);

        foreach ($refection->getProperties() as $property) {
            if (! $property->isStatic()
                && ($declaring_class_name = $property->getDeclaringClass()->getName()) !== PHPHUnitTestCase::class
                && $declaring_class_name !== IlluminateTestCase::class
            ) {
                $property->setAccessible(true);
                $property->setValue($this, null);
            }
        }

        unset($refection);

        \gc_collect_cycles();
        \gc_mem_caches();
    }

    /**
     * Enable clear memory before the application is destroyed for this test class.
     *
     * @return void
     */
    public function enableCleanMemory(): void
    {
        $this->beforeApplicationDestroyed($this->cleanMemoryClosureFactory());
    }

    /**
     * Clear memory closure factory.
     *
     * @return Closure
     */
    protected function cleanMemoryClosureFactory(): Closure
    {
        return function () {
            $this->clearMemory();
        };
    }
}
