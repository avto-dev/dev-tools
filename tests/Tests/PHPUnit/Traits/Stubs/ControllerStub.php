<?php

declare(strict_types = 1);

namespace Tests\AvtoDev\DevTools\Tests\PHPUnit\Traits\Stubs;

class ControllerStub
{
    public function __invoke()
    {
    }

    public function testAction()
    {
        return true;
    }
}
