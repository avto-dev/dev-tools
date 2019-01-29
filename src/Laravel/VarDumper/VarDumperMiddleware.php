<?php

declare(strict_types = 1);

namespace AvtoDev\DevTools\Laravel\VarDumper;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * @link https://laravel.com/docs/5.5/middleware#defining-middleware Before & After Middleware
 */
class VarDumperMiddleware
{
    /**
     * @var DumpStack
     */
    protected $stack;

    /**
     * Middleware constructor.
     *
     * @param DumpStack $stack
     */
    public function __construct(DumpStack $stack)
    {
        $this->stack = $stack;
    }

    /**
     * Modify response after the request is handled by the application.
     *
     * @param Request $request
     * @param Closure $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        /** @var Response $response */
        $response = $next($request);

        if ($this->stack->count() > 0) {
            $dumped = '';

            foreach ($this->stack->all() as $item) {
                $dumped .= $item . \PHP_EOL;
            }

            $this->stack->clear();

            $response->setContent($dumped . $response->getContent());
        }

        return $response;
    }
}
