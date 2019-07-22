<?php

declare(strict_types = 1);

namespace Tests\AvtoDev\DevTools;

use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\ExpectationFailedException;

abstract class AbstractTestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * Assert that code inside closure throws assertion exception.
     *
     * @param callable $closure
     *
     * @return bool
     */
    protected function assertAssertationInsideClosureFailed(callable $closure): bool
    {
        $caught = false;

        try {
            $closure();
        } catch (ExpectationFailedException $e) {
            $caught = true;
        } catch (AssertionFailedError $e) {
            $caught = true;
        }

        return $caught;
    }
}
