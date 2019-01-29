<?php

namespace dev;

use Illuminate\Foundation\Application as LaravelApplication;
use AvtoDev\DevTools\Exceptions\VarDumperException;
use AvtoDev\DevTools\Laravel\VarDumper\DumpStack;
use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\HtmlDumper;

/**
 * Detects ran using CLI.
 *
 * @return bool
 */
function ran_using_cli(): bool
{
    $force_disable = isset($_SERVER[$name = 'DEV_DUMP_NON_CLI']) && (((bool) $_SERVER[$name]) === true);

    return ! $force_disable && \in_array(\PHP_SAPI, ['cli', 'phpdbg'], true);
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
        // "\dd()" is included into next packages:
        // - "illuminate/support" since v4.0 up to v5.6 included
        // - "symfony/var-dumper" since v4.1 and above
        if (\function_exists('\dd')) {
            \dd(...$arguments);
        } else {
            foreach ($arguments as $argument) {
                \dump($argument);
            }

            die(1);
        }
    } else {
        $dumper = new HtmlDumper;
        $dump   = '';

        foreach ($arguments as $argument) {
            $dump = $dumper->dump((new VarCloner)->cloneVar($argument), true) . PHP_EOL;
        }

        throw new VarDumperException(\trim($dump));
    }
}

function dump(...$arguments)
{
    // Ran under CLI?
    if (ran_using_cli() === true) {
        \dump(...$arguments);
    } else {
        $dumper = new HtmlDumper;

        // For Laravel application
        if (\function_exists('\app') && \class_exists(LaravelApplication::class)) {
            /** @var DumpStack $stack */
            $stack = \app(DumpStack::class);

            foreach ($arguments as $argument) {
                $stack->push($dumper->dump((new VarCloner)->cloneVar($argument), true));
            }
        }
    }
}

