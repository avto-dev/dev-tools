<?php

declare(strict_types = 1);

namespace AvtoDev\DevTools\Laravel\VarDumper;

class DumpStack implements DumpStackInterface
{
    /**
     * @var string[]
     */
    protected $stack = [];

    /**
     * {@inheritdoc}
     */
    public function push(string $data)
    {
        $this->stack[] = $data;
    }

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        $this->stack = [];
    }

    /**
     * {@inheritdoc}
     */
    public function all(): array
    {
        return $this->stack;
    }

    /**
     * {@inheritdoc}
     */
    public function count(): int
    {
        return \count($this->stack);
    }
}
