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
     * Important! This method ignores microseconds.
     *
     * Usage example:
     * <code>
     *   $this->assertCarbonParseEquals('2012-10-30', Carbon::create(2012, 10, 30), true);
     *   $this->assertCarbonParseEquals('2012-10-30 12:24', Carbon::create(2012, 10, 30, 12, 24));
     * </code>
     *
     * @param string   $expected
     * @param DateTime $actual
     * @param bool     $ignore_time
     *
     * @throws AssertionFailedError
     * @throws InvalidArgumentException
     *
     * @return void
     */
    public function assertCarbonParseEquals(string $expected, DateTime $actual, bool $ignore_time = false): void
    {
        $parsed = Carbon::parse($expected);
        $actual = Carbon::instance($actual);

        if ($ignore_time === true) {
            $time = PHP_VERSION_ID >= 70100
                ? [0, 0, 0, 0]
                : [0, 0, 0];

            foreach ([$parsed, $actual] as $carbon) {
                /* @var Carbon $carbon */
                $carbon->setTime(...$time);
            }
        }

        $this->assertEquals(
            0,
            $parsed->diffInSeconds($actual),
            "Parsed result [{$parsed->toIso8601String()}] does not equals [{$actual->toIso8601String()}]"
        );
    }
}
