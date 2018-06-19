<?php

declare(strict_types = 1);

namespace AvtoDev\DevTools\Tests\PHPUnit\Traits;

use DateTime;
use Carbon\Carbon;
use PHPUnit\Framework\AssertionFailedError;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

trait CarbonAssertionsTrait
{
    /**
     * Asserts that string (parsed using `Carbon::parse(...)`) equals passed DateTime.
     *
     * Optional - you can ignore time comparison.
     *
     * @param string|null $expected
     * @param DateTime    $actual
     * @param bool        $ignore_time
     *
     * @throws AssertionFailedError
     * @throws InvalidArgumentException
     *
     * @return void
     */
    public static function assertCarbonParseEquals(string $expected = null, DateTime $actual, $ignore_time = false)
    {
        if ($ignore_time === true) {
            $actual = clone $actual;

            // $microseconds parameter added since PHP 7.1.0
            // @link http://php.net/manual/en/datetime.settime.php
            $time = PHP_VERSION_ID >= 70100
                ? [0, 0, 0, 0]
                : [0, 0, 0];

            $actual->setTime(...$time);
        }

        static::assertEquals(Carbon::parse($expected), $actual);
    }
}
