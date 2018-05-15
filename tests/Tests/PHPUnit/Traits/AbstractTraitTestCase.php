<?php

namespace Tests\AvtoDev\DevTools\Tests\PHPUnit\Traits;

use PHPUnit\Framework\AssertionFailedError;
use Tests\AvtoDev\DevTools\AbstractTestCase;
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

abstract class AbstractTraitTestCase extends AbstractTestCase
{
    /**
     * Fabric method for class that uses tested trait.
     *
     * @return mixed
     */
    abstract protected function classUsedTraitFactory();

    /**
     * @param string $method_name
     * @param array  $valid
     * @param array  $invalid
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    protected function makeAssertTest(string $method_name, array $valid, array $invalid)
    {
        $instance = $this->classUsedTraitFactory();

        foreach ($valid as $valid_assert) {
            $instance::{$method_name}($valid_assert);
        }

        foreach ($invalid as $invalid_assert) {
            $caught = false;

            try {
                $instance::{$method_name}($invalid_assert);
            } catch (ExpectationFailedException $e) {
                $caught = true;
            } catch (AssertionFailedError $e) {
                $caught = true;
            }

            $this->assertTrue($caught, var_export($invalid_assert, true));
        }
    }
}
