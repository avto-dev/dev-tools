<?php

namespace Tests\AvtoDev\DevTools\Tests\PHPUnit\Traits;

use PHPUnit\Framework\AssertionFailedError;
use Tests\AvtoDev\DevTools\AbstractTestCase;
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

class AdditionalAssertionsTraitTest extends AbstractTestCase
{
    /**
     * @throws ExpectationFailedException
     * @throws AssertionFailedError
     * @throws InvalidArgumentException
     *
     * @return void
     */
    public function testAssertIsNumeric()
    {
        foreach ([1, 1.0, 0.00001, '1', '1.0', '0.00001', [1, 0.00001], [1]] as $valid_assert) {
            AdditionalAssertionsTraitStub::assertIsNumeric($valid_assert);
        }

        foreach (['foo', [1, 'foo']] as $invalid_assert) {
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
     * @throws ExpectationFailedException
     * @throws AssertionFailedError
     * @throws InvalidArgumentException
     *
     * @return void
     */
    public function testAssertIsArray()
    {
        foreach ([[], [null], [1], [1, 2], [1, [null]]] as $valid_assert) {
            AdditionalAssertionsTraitStub::assertIsArray($valid_assert);
        }

        foreach (['foo', 1, new \stdClass] as $invalid_assert) {
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
