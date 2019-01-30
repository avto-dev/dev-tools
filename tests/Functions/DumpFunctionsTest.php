<?php

declare(strict_types = 1);

namespace Tests\AvtoDev\DevTools\Functions;

use AvtoDev\DevTools\Exceptions\VarDumperException;
use AvtoDev\DevTools\Laravel\VarDumper\ServiceProvider;
use AvtoDev\DevTools\Laravel\VarDumper\DumpStackInterface;
use AvtoDev\DevTools\Tests\PHPUnit\Traits\CreatesApplicationTrait;

class DumpFunctionsTest extends \Illuminate\Foundation\Testing\TestCase
{
    use CreatesApplicationTrait;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        // Required for '\dev\dump()' function testing
        $this->app->register(ServiceProvider::class);

        unset($_SERVER['DEV_DUMP_CLI_MODE']);
    }

    /**
     * @return void
     */
    public function testRanUsingCliFunction()
    {
        $this->assertTrue(\function_exists('\\dev\\ran_using_cli'));

        $this->assertTrue(\dev\ran_using_cli());

        $_SERVER['DEV_DUMP_CLI_MODE'] = true;
        $this->assertTrue(\dev\ran_using_cli());

        $_SERVER['DEV_DUMP_CLI_MODE'] = false;
        $this->assertFalse(\dev\ran_using_cli());
    }

    /**
     * @return void
     */
    public function testDdFunctionExists()
    {
        $this->assertTrue(\function_exists('\\dev\\dd'));
    }

    /**
     * @return void
     */
    public function testDdFunctionThrowAnExceptionInNonCliMode()
    {
        $value1 = 'foo_' . \random_int(1, 255);
        $value2 = 'bar_' . \random_int(1, 255);

        $this->expectException(VarDumperException::class);
        $this->expectExceptionMessageRegExp("~${value1}.*${value2}~s");

        $_SERVER['DEV_DUMP_CLI_MODE'] = false;

        \dev\dd($value1, $value2);
    }

    /**
     * @return void
     */
    public function testDumpFunctionExists()
    {
        $this->assertTrue(\function_exists('\\dev\\dump'));
    }

    /**
     * @return void
     */
    public function testDumpFunctionPushIntoStackInNonCliMode()
    {
        $_SERVER['DEV_DUMP_CLI_MODE'] = false;

        /** @var DumpStackInterface $stack */
        $stack = $this->app->make(DumpStackInterface::class);

        \dev\dump($value1 = 'foo_' . \random_int(1, 255), $value2 = 'bar_' . \random_int(1, 255));

        $this->assertCount(2, $stack);
        $this->assertRegExp("~${value1}~s", $stack->all()[0]);
        $this->assertRegExp("~${value2}~s", $stack->all()[1]);
    }
}
