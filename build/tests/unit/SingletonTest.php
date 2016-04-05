<?php

namespace Unit;

use \PHPUnit_Framework_TestCase as TestCase;
use \Unit\Containers\FooContainer;
use \Unit\Containers\BarContainer;;
use \Unit\Concretes\ConcreteWrapper as Wrapper;
use \Xemoe\Contracts\WrapperContract;

class ShellTest extends TestCase
{
    public function setUp()
    {
        FooContainer::clean();
        BarContainer::clean();
    }

    //
    // Create 2 different of stub wrapper class
    // 1. fooWrapper return foo on exec(['foo'])
    // 2. barWrapper return bar on exec(['foo'])
    //
    protected function fooWrapper()
    {
        $stub = $this->getMockBuilder(WrapperContract::class)->getMock();

        $map = [
            'exec' => [
                0 => [ ['foo'], [], 'foo' ],
            ],
        ];

        $stub->expects($this->any())
            ->method('exec')
            ->will($this->returnValueMap($map['exec']));

        return $stub;
    }

    protected function barWrapper()
    {
        $stub = $this->getMockBuilder(WrapperContract::class)->getMock();

        $map = [
            'exec' => [
                0 => [ ['foo'], [], 'bar' ],
            ],
        ];

        $stub->expects($this->any())
            ->method('exec')
            ->will($this->returnValueMap($map['exec']));

        return $stub;
    }

    public function testAttachWrapper_withFooAndBarContainer_shouldReturnExpectValue()
    {
        FooContainer::attach(static::fooWrapper(), WrapperContract::class);
        BarContainer::attach(static::barWrapper(), WrapperContract::class);

        $this->assertEquals('foo', FooContainer::getInstance()->resolve(WrapperContract::class)->exec(['foo']));
        $this->assertEquals('bar', BarContainer::getInstance()->resolve(WrapperContract::class)->exec(['foo']));
    }
}
