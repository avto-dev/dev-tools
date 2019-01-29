<?php

declare(strict_types = 1);

namespace AvtoDev\DevTools\Laravel\VarDumper;

use Illuminate\Cache\CacheManager;
use Illuminate\Contracts\Cache\Repository;

class DumpStack implements \Countable
{
    /**
     * Cache key name.
     */
    const CACHE_KEY_NAME = 'avtodev.dev.dump.cache';

    /**
     * @var Repository
     */
    protected $cache;

    /**
     * DumpStack constructor.
     *
     * @param CacheManager $cache_manager Required for share data between web-workers
     */
    public function __construct(CacheManager $cache_manager)
    {
        $this->cache = $cache_manager->store();
    }

    /**
     * @param string $data
     */
    public function push(string $data)
    {
        $current = $this->all();

        $current[] = $data;

        $this->cache->forever(static::CACHE_KEY_NAME, $current);
    }

    /**
     * @return void
     */
    public function clear()
    {
        $this->cache->forget(static::CACHE_KEY_NAME);
    }

    /**
     * @return string[]
     */
    public function all(): array
    {
        return (array) $this->cache->get(static::CACHE_KEY_NAME, []);
    }

    /**
     * {@inheritdoc}
     */
    public function count(): int
    {
        return \count($this->all());
    }
}
