<?php

namespace AvtoDev\DevTools\Tests\PHPUnit\Traits;

use Illuminate\Console\Command;
use PHPUnit\Framework\Exception;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Illuminate\Contracts\Console\Kernel as ConsoleKernelContract;

/**
 * @mixin \Illuminate\Foundation\Testing\TestCase
 */
trait LaravelCommandsAssertionsTrait
{
    /**
     * Assert that command registered in artisan.
     *
     * @param string|Command $command Command name|class_name|instance that must be checked
     * @param string         $message
     *
     * @throws InvalidArgumentException
     *
     * @return void
     */
    public function assertArtisanCommandExists($command, string $message = ''): void
    {
        if (\is_object($command)) {
            $command = \get_class($command);
        }

        // Array of registered commands. ['command_name'=>instance]
        $all_commands = $this->app->make(ConsoleKernelContract::class)->all();

        $command_exists = \array_key_exists($command, $all_commands);

        if (! $command_exists) {
            foreach ($all_commands as $command_instance) {
                if ($command_exists = ($command_instance instanceof $command)) {
                    break;
                }
            }
        }

        $this->assertTrue($command_exists, $message === ''
            ? "Command {$command} does not exists"
            : $message);
    }

    /**
     * Asserts that command has specific option.
     *
     * @param string|Command $command Command name|class_name|instance that must be checked
     * @param string         $option  Option name
     * @param string         $message
     *
     * @throws InvalidArgumentException
     *
     * @return void
     */
    public function assertArtisanCommandHasOption(string $option, $command, string $message = ''): void
    {
        $command = $this->buildCommand($command);

        $this->assertTrue($command->getDefinition()->hasOption($option), $message === ''
            ? \sprintf('Command %s has no option "%s"', \get_class($command), $option)
            : $message);
    }

    /**
     * Asserts that command has specific argument.
     *
     * @param string|Command $command  Command name|class_name|instance that must be checked
     * @param string         $argument Argument name
     * @param string         $message
     *
     * @throws InvalidArgumentException
     *
     * @return void
     */
    public function assertArtisanCommandHasArgument(string $argument, $command, string $message = ''): void
    {
        $command = $this->buildCommand($command);

        $this->assertTrue($command->getDefinition()->hasArgument($argument), $message === ''
            ? \sprintf('Command %s has no argument "%s"', get_class($command), $argument)
            : $message);
    }

    /**
     * Assert that command has specific option shortcut.
     *
     * @param string|Command $command  Command name|class_name|instance that must be checked
     * @param string         $shortcut Shortcut name
     * @param string         $message
     *
     * @throws InvalidArgumentException
     *
     * @return void
     */
    public function assertArtisanCommandHasOptionShortcut(string $shortcut, $command, string $message = ''): void
    {
        $command = $this->buildCommand($command);

        $this->assertTrue($command->getDefinition()->hasShortcut($shortcut), $message === ''
            ? \sprintf('Command %s has no shortcut "%s"', get_class($command), $shortcut)
            : $message);
    }

    /**
     * Assert that command shortcut belongs to specific option.
     *
     * @param string|Command $command  Command name|class_name|instance that must be checked
     * @param string         $shortcut Shortcut name
     * @param string         $option   Option name
     * @param string         $message
     *
     * @throws Exception
     * @throws InvalidArgumentException
     *
     * @return void
     */
    public function assertArtisanCommandShortcutBelongToOption(string $shortcut,
                                                               string $option,
                                                               $command,
                                                               string $message = ''): void
    {
        $this->assertArtisanCommandHasOptionShortcut($shortcut, $command, $message);

        $command = $this->buildCommand($command);

        $default_message = \sprintf(
            'Shortcut "%s" in command "%s" does not belong to option "%s" ',
            $shortcut,
            \get_class($command),
            $option
        );

        $this->assertEquals(
            $option,
            $this->getObjectAttribute($command->getDefinition(), 'shortcuts')[$shortcut] ?? '',
            $message === ''
                ? $default_message
                : $message
        );
    }

    /**
     * Assert that artisan command has description.
     *
     * @param string|Command $command Command name|class_name|instance that must be checked
     * @param string         $message
     *
     * @throws InvalidArgumentException
     *
     * @return void
     */
    public function assertArtisanCommandDescriptionNotEmpty($command, string $message = ''): void
    {
        $command = $this->buildCommand($command);

        $this->assertNotEmpty($command->getDescription(), $message === ''
            ? \sprintf('Command "%s" has empty description', \get_class($command))
            : $message);
    }

    /**
     * Assert that artisan command has description.
     *
     * @param string|Command $command Command name|class_name|instance that must be checked
     * @param string         $pattern Regular expression
     * @param string         $message
     *
     * @throws InvalidArgumentException
     *
     * @return void
     */
    public function assertArtisanCommandDescriptionRegExp(string $pattern, $command, string $message = ''): void
    {
        $command = $this->buildCommand($command);

        $this->assertRegExp($pattern, $command->getDescription(), $message === ''
            ? \sprintf('Description of command "%s" does not match regexp "%s"', \get_class($command), $pattern)
            : $message);
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
                $this->assertInstanceOf(Command::class, $command);
            }
        }

        return $command;
    }
}
