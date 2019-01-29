<?php

declare(strict_types = 1);

namespace AvtoDev\DevTools\Exceptions;

use Throwable;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class VarDumperException extends \Exception
{
    /**
     * VarDumperException constructor.
     *
     * @param string         $message
     * @param int            $code
     * @param Throwable|null $previous
     */
    public function __construct(string $message,
                                int $code = Response::HTTP_INTERNAL_SERVER_ERROR,
                                Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Report the exception.
     *
     * Since "illuminate/framework" v5.5.
     *
     * @link https://laravel.com/docs/5.5/errors#renderable-exceptions
     *
     * @return void
     */
    public function report()
    {
        // Do nothing
    }

    /**
     * Render the exception into an HTTP response.
     *
     * Since "illuminate/framework" v5.5.
     *
     * @link https://laravel.com/docs/5.5/errors#renderable-exceptions
     *
     * @param Request|null $request
     *
     * @return Response
     */
    public function render(Request $request = null): Response
    {
        return new Response(static::generateView($this->getMessage()), $this->getCode());
    }

    /**
     * Generate HTML representation of some content.
     *
     * @param string $content
     *
     * @return string
     */
    protected static function generateView($content):string
    {
        return <<<EOT
<html>
    <head></head>
    <body>
        $content
    </body>
</html>
EOT;
    }
}
