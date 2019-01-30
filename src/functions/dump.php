<?php

namespace dev;

use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\HtmlDumper;
use AvtoDev\DevTools\Exceptions\VarDumperException;
use AvtoDev\DevTools\Laravel\VarDumper\DumpStackInterface;
use Illuminate\Foundation\Application as LaravelApplication;

/**
 * Detects ran using CLI.
 *
 * @return bool
 */
function ran_using_cli(): bool
{
    if (isset($_SERVER[$name = 'DEV_DUMP_CLI_MODE'])) {
        return ((bool) $_SERVER[$name]) === true;
    }

    return \in_array(\PHP_SAPI, ['cli', 'phpdbg'], true);
}

/**
 * Dump passed values and die (or throw an Exception).
 *
 * @param mixed ...$arguments
 *
 * @throws VarDumperException
 */
function dd(...$arguments)
{
    // Ran under CLI?
    if (ran_using_cli() === true) {
        // @codeCoverageIgnoreStart

        // "\dd()" is included into next packages:
        // - "illuminate/support" since v4.0 up to v5.6 included
        // - "symfony/var-dumper" since v4.1 and above
        if (\function_exists('\\dd')) {
            \dd(...$arguments);
        } else {
            foreach ($arguments as $argument) {
                \dump($argument);
            }

            die(1);
        }
        // @codeCoverageIgnoreEnd
    } else {
        $dumper = new HtmlDumper;

        $parts = \array_map(function ($argument) use ($dumper) {
            return $dumper->dump((new VarCloner)->cloneVar($argument), true);
        }, $arguments);

        throw new VarDumperException(\implode(\PHP_EOL, $parts));
    }
}

function dump(...$arguments)
{
    // Ran under CLI?
    if (ran_using_cli() === true) {
        // @codeCoverageIgnoreStart

        \dump(...$arguments);

    // @codeCoverageIgnoreEnd
    } else {
        $dumper = new HtmlDumper;

        // For Laravel application
        if (\function_exists('\\app') && \class_exists(LaravelApplication::class)) {
            /** @var DumpStackInterface $stack */
            $stack = \app(DumpStackInterface::class);

            foreach ($arguments as $argument) {
                $stack->push($dumper->dump((new VarCloner)->cloneVar($argument), true));
            }
        }
    }
}
