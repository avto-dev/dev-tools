<?php

declare(strict_types = 1);

namespace AvtoDev\DevTools\Tests\PHPUnit\Traits;

use Tarampampam\GuzzleUrlMock\UrlsMockHandler;

trait WithGuzzleMocking
{
    /**
     * @var UrlsMockHandler
     */
    protected $guzzle_handler;

    /**
     * Get HTTP mock handler.
     *
     * @return UrlsMockHandler
     */
    public function guzzleHandler(): UrlsMockHandler
    {
        return $this->guzzle_handler;
    }

    /**
     * Make mock handler instance and optionally override HTTP client bindings (in DI container, for example).
     *
     * @return void
     */
    public function enableGuzzleMocking(): void
    {
        $this->guzzle_handler = new UrlsMockHandler;

        if (\method_exists($this, $override_method_name = 'overrideGuzzleClientBinding')) {
            $this->$override_method_name($this->guzzle_handler);
        }
    }
}
