<?php

namespace Tests\AvtoDev\DevTools\Tests\PHPUnit\Traits;

use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\AssertionFailedError;
use Tests\AvtoDev\DevTools\AbstractTestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

class AdditionalAssertionsTraitTest extends AbstractTestCase
{
    /**
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws AssertionFailedError
     * @throws InvalidArgumentException
     */
    public function testAssertIsNumeric()
    {
        foreach ([1, 1.0, 0.00001, '1', '1.0', '0.00001', [1, 0.00001], [1]] as $valid_assert) {
            AdditionalAssertionsTraitStub::assertIsNumeric($valid_assert);
        }

        foreach (['foo', function () {}, [1, 'foo']] as $invalid_assert) {
            $caught = false;

            try {
                AdditionalAssertionsTraitStub::assertIsNumeric($invalid_assert);
            } catch (ExpectationFailedException $e) {
                $caught = true;
            }

            $this->assertTrue($caught, var_export($invalid_assert, true));
        }
    }

    /**
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws AssertionFailedError
     * @throws InvalidArgumentException
     */
    public function testAssertIsArray()
    {
        foreach ([[], [null], [1], [1, 2], [1, [null]]] as $valid_assert) {
            AdditionalAssertionsTraitStub::assertIsArray($valid_assert);
        }

        foreach (['foo', 1, new \stdClass, function () {}] as $invalid_assert) {
            $caught = false;

            try {
                AdditionalAssertionsTraitStub::assertIsArray($invalid_assert);
            } catch (ExpectationFailedException $e) {
                $caught = true;
            }

            $this->assertTrue($caught, var_export($invalid_assert, true));
        }
    }
}
