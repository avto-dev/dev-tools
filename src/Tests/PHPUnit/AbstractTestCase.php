<?php

declare(strict_types=1);

namespace AvtoDev\DevTools\Tests\PHPUnit;

use PHPUnit\Framework\TestCase;

abstract class AbstractTestCase extends TestCase
{
    use Traits\AdditionalAssertionsTrait,
        Traits\InstancesAccessorsTrait;
}
