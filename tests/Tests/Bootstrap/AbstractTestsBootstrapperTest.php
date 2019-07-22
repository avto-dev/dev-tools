<?php

declare(strict_types = 1);

namespace Tests\AvtoDev\DevTools\Tests\Bootstrap;

use Exception;
use Tests\AvtoDev\DevTools\AbstractTestCase;
use AvtoDev\DevTools\Tests\Bootstrap\AbstractTestsBootstrapper;

/**
 * @covers \AvtoDev\DevTools\Tests\Bootstrap\AbstractTestsBootstrapper<extended>
 */
class AbstractTestsBootstrapperTest extends AbstractTestCase
{
    /**
     * @throws \Exception
     */
    public function testBootstrapper(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessageRegExp('~stub is works~');

        new class extends AbstractTestsBootstrapper {
            /**
             * This method must be called automatically.
             *
             * @throws \Exception
             */
            protected function bootFoo()
            {
                throw new Exception('stub is works');
            }
        };
    }
}
