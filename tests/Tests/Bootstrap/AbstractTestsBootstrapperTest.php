<?php

namespace Tests\AvtoDev\DevTools\Tests\Bootstrap;

use Exception;
use Tests\AvtoDev\DevTools\AbstractTestCase;
use AvtoDev\DevTools\Tests\Bootstrap\AbstractTestsBootstrapper;

class AbstractTestsBootstrapperTest extends AbstractTestCase
{
    /**
     * @throws \Exception
     */
    public function testBootstrapper()
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
