<?php

declare(strict_types = 1);

namespace Tests\AvtoDev\DevTools\Tests\PHPUnit\Traits\Stubs;

class ControllerStub
{
    public function testAction()
    {
        return true;
    }

    public function __invoke()
    {
    }
}
