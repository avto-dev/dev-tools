<?php

namespace AvtoDev\DevTools\Tests\PHPUnit\Traits;

use Illuminate\Console\Command;
use PHPUnit\Framework\Exception;
use AvtoDev\DevTools\Tests\PHPUnit\AbstractLaravelTestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Illuminate\Contracts\Console\Kernel as ConsoleKernelContract;

/**
 * Trait LaravelCommandsAssertionsTrait.
 *
 * @mixin AbstractLaravelTestCase
 */
trait LaravelCommandsAssertionsTrait
{
    /**
     * Assert that command registered in artisan.
     *
     * @param string|Command $command Command name|class_name|instance that must be checked
     *
     * @throws InvalidArgumentException
     */
    public function assertArtisanCommandExists($command)
    {
        if (\is_object($command)) {
            $command = get_class($command);
        }

        // Array of registered commands. ['command_name'=>instance]
        $all_commands = $this->app->make(ConsoleKernelContract::class)->all();

        $message = "Command {$command} does not exists";

        $command_exists = \array_key_exists($command, $all_commands);

        if (! $command_exists) {
            foreach ($all_commands as $command_instance) {
                if ($command_exists = $command_instance instanceof $command) {
                    break;
                }
            }
        }

        static::assertTrue($command_exists, $message);
    }

    /**
     * Asserts that command has specific option.
     *
     * @param string|Command $command Command name|class_name|instance that must be checked
     * @param string         $option  Option name
     *
     * @throws InvalidArgumentException
     */
    public function assertArtisanCommandHasOption(string $option, $command)
    {
        $command = $this->buildCommand($command);

        $message = sprintf('Command %s has no option "%s"', get_class($command), $option);

        static::assertTrue($command->getDefinition()->hasOption($option), $message);
    }

    /**
     * Asserts that command has specific argument.
     *
     * @param string|Command $command  Command name|class_name|instance that must be checked
     * @param string         $argument Argument name
     *
     * @throws InvalidArgumentException
     */
    public function assertArtisanCommandHasArgument(string $argument, $command)
    {
        $command = $this->buildCommand($command);

        $message = sprintf('Command %s has no argument "%s"', get_class($command), $argument);

        static::assertTrue($command->getDefinition()->hasArgument($argument), $message);
    }

    /**
     * Assert that command has specific option shortcut.
     *
     * @param string|Command $command  Command name|class_name|instance that must be checked
     * @param string         $shortcut Shortcut name
     *
     * @throws InvalidArgumentException
     */
    public function assertArtisanCommandHasOptionShortcut(string $shortcut, $command)
    {
        $command = $this->buildCommand($command);

        $message = sprintf('Command %s has no shortcut "%s"', get_class($command), $shortcut);

        static::assertTrue($command->getDefinition()->hasShortcut($shortcut), $message);
    }

    /**
     * Assert that command shortcut belongs to specific option.
     *
     * @param string|Command $command  Command name|class_name|instance that must be checked
     * @param string         $shortcut Shortcut name
     * @param string         $option   Option name
     *
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function assertArtisanCommandShortcutBelongToOption(string $shortcut, string $option, $command)
    {
        $this->assertArtisanCommandHasOptionShortcut($shortcut, $command);

        $command = $this->buildCommand($command);

        $message = sprintf(
            'Shortcut "%s" in command "%s" does not belong to option "%s" ',
            $shortcut,
            get_class($command),
            $option
        );

        static::assertEquals(
            $option,
            $this->getObjectAttribute($command->getDefinition(), 'shortcuts')[$shortcut] ?? '',
            $message
        );
    }

    /**
     * Assert that artisan command has description.
     *
     * @param string|Command $command Command name|class_name|instance that must be checked
     *
     * @throws InvalidArgumentException
     */
    public function assertArtisanCommandDescriptionNotEmpty($command)
    {
        $command = $this->buildCommand($command);

        $message = sprintf('Command "%s" has empty description', get_class($command));

        static::assertNotEmpty($command->getDescription(), $message);
    }

    /**
     * Assert that artisan command has description.
     *
     * @param string|Command $command Command name|class_name|instance that must be checked
     * @param string         $pattern Regular expression     *
     *
     * @throws InvalidArgumentException
     */
    public function assertArtisanCommandDescriptionRegExp(string $pattern, $command)
    {
        $command = $this->buildCommand($command);

        $message = sprintf('Description of command "%s" does not match regexp "%s"', get_class($command), $pattern);

        static::assertRegExp($pattern, $command->getDescription(), $message);
    }

    /**
     * Build command.
     *
     * @param string|Command $command Command name|class_name|instance that must be checked
     *
     * @throws InvalidArgumentException
     *
     * @return Command
     */
    protected function buildCommand($command): Command
    {
        /** @var ConsoleKernelContract $artisan */
        $artisan = $this->app->make(ConsoleKernelContract::class);

        if (! \is_object($command)) {
            if (\array_key_exists($command, $artisan->all())) {
                $command = $artisan->all()[$command];
            } elseif (\class_exists($command)) {
                $command = $this->app->make($command);
                static::assertInstanceOf(Command::class, $command);
            }
        }

        return $command;
    }
}
