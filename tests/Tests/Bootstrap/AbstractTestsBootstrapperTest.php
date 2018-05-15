<?php

namespace Tests\AvtoDev\DevTools\Tests\Bootstrap;

use Tests\AvtoDev\DevTools\AbstractTestCase;
use AvtoDev\DevTools\Tests\Bootstrap\AbstractTestsBootstrapper;
use AvtoDev\DevTools\Tests\PHPUnit\Traits\CreatesApplicationTrait;

class AbstractTestsBootstrapperTest extends AbstractTestCase
{
    /**
     * @throws \Exception
     */
    public function testBootstrapper()
    {
        $this->expectExceptionMessageRegExp('~stub is works~');

        new class extends AbstractTestsBootstrapper {
            use CreatesApplicationTrait;

            protected function bootLog()
            {
                $this->log();

                return true;
            }

            /**
             * This method must be called automatically.
             *
             * @throws \Exception
             */
            protected function bootFoo()
            {
                throw new \Exception('stub is works');
            }
        };
    }
}
