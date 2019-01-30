<?php

declare(strict_types = 1);

namespace AvtoDev\DevTools\Laravel\VarDumper;

use Countable;

interface DumpStackInterface extends Countable
{
    /**
     * Push an element into stack.
     *
     * @param string $data
     */
    public function push(string $data);

    /**
     * Clear stack.
     *
     * @return void
     */
    public function clear();

    /**
     * Get all stack elements.
     *
     * @return string[]
     */
    public function all(): array;
}
