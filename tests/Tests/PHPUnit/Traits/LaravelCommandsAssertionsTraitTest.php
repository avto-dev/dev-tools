<?php

declare(strict_types = 1);

namespace Tests\AvtoDev\DevTools\Tests\PHPUnit\Traits;

use Illuminate\Foundation\Application;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\ExpectationFailedException;
use Illuminate\Console\Application as ArtisanApplication;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use AvtoDev\DevTools\Tests\PHPUnit\Traits\CreatesApplicationTrait;
use AvtoDev\DevTools\Tests\PHPUnit\Traits\InstancesAccessorsTrait;
use Tests\AvtoDev\DevTools\Tests\PHPUnit\Traits\Stubs\SignatureCommand;
use Tests\AvtoDev\DevTools\Tests\PHPUnit\Traits\Stubs\StructureCommand;
use AvtoDev\DevTools\Tests\PHPUnit\Traits\LaravelCommandsAssertionsTrait;

/**
 * @covers \AvtoDev\DevTools\Tests\PHPUnit\Traits\LaravelCommandsAssertionsTrait<extended>
 */
class LaravelCommandsAssertionsTraitTest extends \Illuminate\Foundation\Testing\TestCase
{
    use CreatesApplicationTrait, InstancesAccessorsTrait, LaravelCommandsAssertionsTrait;

    /**
     * Signature configured command.
     *
     * @var SignatureCommand
     */
    protected $signature_command;

    /**
     * Structure configured command.
     *
     * @var SignatureCommand
     */
    protected $structure_command;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->signature_command = resolve(SignatureCommand::class);
        $this->structure_command = resolve(StructureCommand::class);
    }

    /**
     * Check assertArtisanCommandExists.
     *
     * @throws InvalidArgumentException
     */
    public function testAssertArtisanCommandExists(): void
    {
        $this->makeAssertTest(
            'assertArtisanCommandExists',
            [
                SignatureCommand::class,
                'test:signature',
                StructureCommand::class,
                'test:structure',
                $this->signature_command,
                $this->structure_command,
            ],
            [
                'not_found_name',
            ]
        );
    }

    /**
     * Check assertArtisanCommandHasOption.
     *
     * @throws InvalidArgumentException
     */
    public function testAssertArtisanCommandHasOption(): void
    {
        $this->makeAssertTest(
            'assertArtisanCommandHasOption',
            [
                'option',
            ],

            [
                'not_found_option',
            ],
            $this->signature_command
        );

        $this->makeAssertTest(
            'assertArtisanCommandHasOption',
            [
                'option',
            ],

            [
                'not_found_option',
            ],
            $this->structure_command
        );
    }

    /**
     * Check assertArtisanCommandHasArgument.
     *
     * @throws InvalidArgumentException
     */
    public function testAssertArtisanCommandHasArgument(): void
    {
        $this->makeAssertTest(
            'assertArtisanCommandHasArgument',
            [
                'argument',
            ],

            [
                'not_found_argument',
            ],
            $this->signature_command
        );

        $this->makeAssertTest(
            'assertArtisanCommandHasArgument',
            [
                'argument',
            ],

            [
                'not_found_argument',
            ],
            $this->structure_command
        );
    }

    /**
     * Check assertArtisanCommandHasOptionShortcut.
     *
     * @throws InvalidArgumentException
     */
    public function testAssertArtisanCommandHasOptionShortcut(): void
    {
        $this->makeAssertTest(
            'assertArtisanCommandHasOptionShortcut',
            [
                'O',
            ],

            [
                'not_found_shortcut',
            ],
            $this->signature_command
        );

        $this->makeAssertTest(
            'assertArtisanCommandHasOptionShortcut',
            [
                'O',
            ],

            [
                'not_found_shortcut',
            ],
            $this->structure_command
        );
    }

    /**
     * Check assertArtisanCommandShortcutBelongToOption.
     *
     * @throws InvalidArgumentException
     */
    public function testAssertArtisanCommandShortcutBelongToOption(): void
    {
        $this->makeAssertTest(
            'assertArtisanCommandShortcutBelongToOption',
            [
                'O',
            ],

            [
                'not_found_shortcut',
            ],
            'option',
            $this->signature_command
        );

        $this->makeAssertTest(
            'assertArtisanCommandShortcutBelongToOption',
            [
                'O',
            ],

            [
                'not_found_shortcut',
            ],
            'option',
            $this->structure_command
        );
    }

    /**
     * Check assertArtisanCommandDescriptionNotEmpty.
     *
     * @throws InvalidArgumentException
     */
    public function testAssertArtisanCommandDescriptionNotEmpty(): void
    {
        $this->makeAssertTest(
            'assertArtisanCommandDescriptionNotEmpty',
            [
                $this->structure_command,
            ],

            [
                $this->signature_command,
            ]
        );
    }

    /**
     * Check assertArtisanCommandDescriptionRegExp.
     *
     * @throws InvalidArgumentException
     */
    public function testAssertArtisanCommandDescriptionRegExp(): void
    {
        $this->makeAssertTest(
            'assertArtisanCommandDescriptionRegExp',
            [
                '/.*word.*/',
            ],

            [
                '/No regexp/',
            ],
            $this->structure_command
        );
    }

    /**
     * Check buildCommand.
     *
     * @throws InvalidArgumentException
     */
    public function testBuildCommand(): void
    {
        $commands = [
            'test:signature' => [
                $this->signature_command,
                'test:signature',
                SignatureCommand::class,
            ],
            'test:structure' => [
                $this->structure_command,
                'test:structure',
                StructureCommand::class,
            ],
        ];
        foreach ($commands as $command_name => $command_aliases) {
            foreach ($command_aliases as $alias) {
                $this->assertEquals(
                    $command_name,
                    $this->buildCommand($alias)->getName()
                );
            }
        }
    }

    /**
     * Make some before application bootstrapped (call `$app->useStoragePath(...)`, `$app->loadEnvironmentFrom(...)`,
     * etc).
     *
     * @see \AvtoDev\DevTools\Tests\PHPUnit\Traits\CreatesApplicationTrait::createApplication
     *
     * @return void
     */
    protected function beforeApplicationBootstrapped(Application $app)
    {
        ArtisanApplication::starting(function (ArtisanApplication $app) {
            $app->resolveCommands([
                SignatureCommand::class,
                StructureCommand::class,
            ]);
        });
    }

    /**
     * @param string $method_name
     * @param array  $valid
     * @param array  $invalid
     * @param mixed  ...$args
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     *
     * @return void
     */
    protected function makeAssertTest(string $method_name, array $valid, array $invalid, ...$args)
    {
        foreach ($valid as $valid_assert) {
            $this->{$method_name}($valid_assert, ...$args);
        }

        foreach ($invalid as $invalid_assert) {
            $caught = false;

            try {
                $this->{$method_name}($invalid_assert, ...$args);
            } catch (ExpectationFailedException $e) {
                $caught = true;
            } catch (AssertionFailedError $e) {
                $caught = true;
            }

            $this->assertTrue($caught, 'Passed invalid value: ' . var_export($invalid_assert, true));
        }
    }
}
