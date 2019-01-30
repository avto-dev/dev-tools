<?php

declare(strict_types = 1);

namespace Tests\AvtoDev\DevTools\Laravel\VarDumper;

use Tests\AvtoDev\DevTools\AbstractTestCase;
use AvtoDev\DevTools\Laravel\VarDumper\DumpStack;

class DumpStackTest extends AbstractTestCase
{
    /**
     * @var DumpStack
     */
    protected $stack;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->stack = new DumpStack;
    }

    /**
     * @return void
     *
     * @covers \AvtoDev\DevTools\Laravel\VarDumper\DumpStack
     */
    public function testInterfaces()
    {
        $this->assertInstanceOf(\Countable::class, $this->stack);
    }

    /**
     * @return void
     *
     * @covers \AvtoDev\DevTools\Laravel\VarDumper\DumpStack::push
     * @covers \AvtoDev\DevTools\Laravel\VarDumper\DumpStack:all
     */
    public function testPush()
    {
        $this->assertCount(0, $this->stack);

        $this->stack->push($value = 'foo_' . \random_int(1, 255));

        $this->assertCount(1, $this->stack);
        $this->assertSame($value, $this->stack->all()[0]);
    }

    /**
     * @return void
     *
     * @covers \AvtoDev\DevTools\Laravel\VarDumper\DumpStack::clear
     * @covers \AvtoDev\DevTools\Laravel\VarDumper\DumpStack::count
     * @covers \AvtoDev\DevTools\Laravel\VarDumper\DumpStack::push
     */
    public function testClearAndCount()
    {
        $this->stack->push('foo');

        $this->assertEquals(1, $this->stack->count());

        $this->stack->clear();

        $this->assertEquals(0, $this->stack->count());
    }

    /**
     * @return void
     *
     * @covers \AvtoDev\DevTools\Laravel\VarDumper\DumpStack::all
     * @covers \AvtoDev\DevTools\Laravel\VarDumper\DumpStack::push
     */
    public function testAll()
    {
        $data = ['baz', 'foo', 'bar'];

        foreach ($data as $item) {
            $this->stack->push($item);
        }

        $this->assertSame($data, $this->stack->all());
    }
}
