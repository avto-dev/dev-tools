<?php

namespace Tests\AvtoDev\DevTools\Tests\PHPUnit\Traits;

use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

/**
 * Class AdditionalAssertionsTraitTest.
 */
class AdditionalAssertionsTraitTest extends AbstractTraitTestCase
{
    /**
     * @throws ExpectationFailedException
     * @throws AssertionFailedError
     * @throws InvalidArgumentException
     *
     * @return void
     */
    public function testsTraitAsserts()
    {
        $this->makeAssertTest(
            'assertIsNumeric',
            [1, 1.0, 0.00001, '1', '1.0', '0.00001', [1, 0.00001], [1]],
            ['foo', [1, 'foo']]
        );

        $this->makeAssertTest(
            'assertNotEmptyArray',
            [[1], ['foo'], [new \stdClass]],
            [[]]
        );

        $this->makeAssertTest(
            'assertIsArray',
            [[], [null], [1], [1, 2], [1, [null]]],
            ['foo', 1, new \stdClass]
        );

        $this->makeAssertTest(
            'assertEmptyArray',
            [[]],
            ['foo', [1], new \stdClass, [[]]]
        );
    }

    /**
     * {@inheritdoc}
     *
     * @return mixed|\PHPUnit\Framework\TestCase
     */
    protected function classUsedTraitFactory()
    {
        return new class extends \PHPUnit\Framework\TestCase {
            use \AvtoDev\DevTools\Tests\PHPUnit\Traits\AdditionalAssertionsTrait;
        };
    }
}
