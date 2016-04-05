<?php

namespace Unit\Containers;

use \PHPUnit_Framework_TestCase as TestCase;
use \Unit\Concretes\ConcreteWrapper as Wrapper;
use \Xemoe\Contracts\ShellContract;
use \Xemoe\Contracts\WrapperContract;
use \Xemoe\Exceptions\InvalidArgumentException;
use \Xemoe\Exceptions\UnresolvableException;
use \Xemoe\ServicesContainer;

class ServicesContainerTest extends TestCase
{
    public function setUp()
    {
        ServicesContainer::clean();
    }

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

    public function testAttach_withEmptyAcceptsArray_shouldThrowExpectedException()
    {
        //
        // @expected
        //
        $expectedException = InvalidArgumentException::class;

        $this->setExpectedException($expectedException);

        $emptyAccepts = [];
        $i = ServicesContainer::getInstance();
        $i->attachable($emptyAccepts);
    }

    public function testAttach_withNotExistingAcceptsContract_shouldThrowExpectedException()
    {
        //
        // @expected
        //
        $expectedException = InvalidArgumentException::class;

        $this->setExpectedException($expectedException);

        $accepts = [
            ShellContract::class,            
        ];
        $i = ServicesContainer::getInstance();
        $i->attachable($accepts);

        $i->attach(static::fooWrapper(), WrapperContract::class);
    }

    public function testResolve_withAttachableContracts_shouldReturnExpectValue()
    {
        //
        // @expected
        //
        $expected = 'foo';

        //
        // Defined attachable contracts
        //
        $accepts = [
            WrapperContract::class,
        ];
        $i = ServicesContainer::getInstance();
        $i->attachable($accepts);

        //
        // Attach wrapper stub
        //
        $i->attach(static::fooWrapper(), WrapperContract::class);

        $result = ServicesContainer::getInstance()->resolve(WrapperContract::class)->exec(['foo']);

        //
        // @asserts
        //
        $this->assertEquals($expected, $result);
    }

    public function testResolve_withNotExistingAcceptsContract_shouldThrowExpectedException()
    {
        //
        // @expected
        //
        $expectedException = UnresolvableException::class;

        $this->setExpectedException($expectedException);

        $accepts = [
            WrapperContract::class,            
        ];
        $i = ServicesContainer::getInstance();
        $i->attachable($accepts);

        $i->attach(static::fooWrapper(), WrapperContract::class);

        //
        // Instead resolve WrapperContract::class
        // we use Wrapper::class (ConcreteWrapper::class)
        // and expected it's throw UnresolvableException
        //
        $result = ServicesContainer::getInstance()->resolve(Wrapper::class)->exec(['foo']);
    }
}
