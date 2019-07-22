<?php

declare(strict_types = 1);

namespace Tests\AvtoDev\DevTools\Tests\PHPUnit\Traits;

use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\ExpectationFailedException;
use Tests\AvtoDev\DevTools\Tests\PHPUnit\Traits\Stubs\TraitOne;
use Tests\AvtoDev\DevTools\Tests\PHPUnit\Traits\Stubs\TraitTwo;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Tests\AvtoDev\DevTools\Tests\PHPUnit\Traits\Stubs\TraitThree;
use Tests\AvtoDev\DevTools\Tests\PHPUnit\Traits\Stubs\WithTraits;

/**
 * @covers \AvtoDev\DevTools\Tests\PHPUnit\Traits\AdditionalAssertionsTrait<extended>
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
    public function testsTraitAsserts(): void
    {
        /* @see AdditionalAssertionsTrait::assertNotEmptyArray */
        $this->makeAssertTest('assertNotEmptyArray', [[1], ['foo'], [new \stdClass]], [[]]);

        /* @see AdditionalAssertionsTrait::assertEmptyArray */
        $this->makeAssertTest('assertEmptyArray', [[]], ['foo', [1], new \stdClass, [[]]]);

        /* @see AdditionalAssertionsTrait::assertEmptyString */
        $this->makeAssertTest('assertEmptyString', [''], ['foo', [1], new \stdClass, []]);
        $this->makeAssertTest('assertEmptyString', ['', ['', '']], ['foo', [1], new \stdClass, []]);

        /* @see AdditionalAssertionsTrait::assertNotEmptyString */
        $this->makeAssertTest('assertNotEmptyString', ['foo'], ['', null]);
        $this->makeAssertTest('assertNotEmptyString', ['foo', ['foo2', 'bar']], ['', null, []]);

        /* @see AdditionalAssertionsTrait::assertStringsEquals */
        $this->makeAssertTest('assertStringsEquals', ['Превед foo'], [], 'превед foo', true);
        $this->makeAssertTest('assertStringsEquals', ['превед foo'], [], 'превед foo', false);

        /* @see AdditionalAssertionsTrait::assertStringsNotEquals */
        $this->makeAssertTest('assertStringsNotEquals', ['Превед foo'], [], 'bar', true);
        $this->makeAssertTest('assertStringsNotEquals', ['превед foo'], [], 'Превед foo', false);

        /* @see AdditionalAssertionsTrait::assertClassExists */
        $this->makeAssertTest('assertClassExists', [\Exception::class, \Throwable::class], ['FooClass']);
        $this->makeAssertTest('assertClassExists', [\Exception::class], ['FooClass', \Throwable::class], false);
        $this->makeAssertTest('assertClassExists', [
            \Exception::class,
            [\Exception::class, \Throwable::class],
        ], ['FooClass']);

        /* @see AdditionalAssertionsTrait::assertHasMethods */
        $this->makeAssertTest('assertHasMethods', [\Exception::class], [\Throwable::class], '__wakeup');
        $this->makeAssertTest('assertHasMethods', [\Exception::class], [\Throwable::class], ['__wakeup']);

        /* @see AdditionalAssertionsTrait::assertClassUsesTraits */
        $this->makeAssertTest('assertClassUsesTraits', [new WithTraits, WithTraits::class], [new \stdClass], [
            TraitOne::class, TraitTwo::class, TraitThree::class,
        ]);

        /* @see AdditionalAssertionsTrait::assertArrayStructure */
        $structures = $this->getStructuresData();
        $this->makeAssertTest(
            'assertArrayStructure',
            $structures['valid'],
            $structures['invalid'],
            $structures['testing_array']
        );

        /* @see AdditionalAssertionsTrait::assertJsonStructure */
        $this->makeAssertTest(
            'assertJsonStructure',
            $structures['valid'],
            $structures['invalid'],
            '{"foo":"var","bar":"var","bus":[{"alice":"var","bob":"var"}]}'
        );
    }

    /**
     * @return void
     */
    public function testAssertJsonStructureFormatException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Passed string has not valid JSON format.');
        /* @see AdditionalAssertionsTrait::assertJsonStructure */
        $this->makeAssertTest('assertJsonStructure', [[]], [[]], 'Invalid JSON');
    }

    /**
     * @return void
     */
    public function testAssertJsonStructureNotArrayException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Passed data is not array.');
        /* @see AdditionalAssertionsTrait::assertJsonStructure */
        $this->makeAssertTest('assertJsonStructure', [[]], [[]], '"valid JSON string"');
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

    /**
     * Get array ['valid' => $validStructureArr, 'invalid' => $invalidStructureArr, 'testing_array' => $testingArray].
     *
     * @return array
     */
    private function getStructuresData(): array
    {
        return [
            // Valid structures
            'valid'         => [
                [
                    'foo',
                    'bar',
                    'bus' => [
                        '*' => [
                            'alice',
                            'bob',
                        ],
                    ],
                ],
                [
                    'foo',
                ],
            ],
            // Invalid structures
            'invalid'       => [
                [
                    'xyz', // no key in first level
                ],
                [
                    'foo',
                    'bar',
                    'bus' => [
                        '*' => [
                            'alice',
                            'bob',
                            'frank', // no key in deep level
                        ],
                    ],
                ],
            ],
            // Testing array
            'testing_array' => [
                'foo' => 'var',
                'bar' => 'var',
                'bus' => [
                    [
                        'alice' => 'var',
                        'bob'   => 'var',
                    ],
                ],
            ],
        ];
    }
}
