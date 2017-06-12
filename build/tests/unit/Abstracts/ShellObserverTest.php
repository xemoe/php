<?php

namespace Unit\Abstracts;

use PHPUnit_Framework_TestCase as TestCase;
use Xemoe\Contracts\WrapperContract;
use Xemoe\Exceptions\ShellErrorException;
use Xemoe\ServicesContainer;
use Unit\Concretes\ConcreteShell as Shell;
use Unit\Concretes\ConcreteWrapper as Wrapper;
use Unit\Concretes\ConcreteShellHistoryObserver as ShellHistoryObserver;

class ShellObserverTest extends TestCase
{
    public function setUp()
    {
        ServicesContainer::clean();

        //
        // Initial observer
        //
        $observer = new ShellHistoryObserver;
        ServicesContainer::attach($observer, ShellHistoryObserver::class);
    }

    protected function shell()
    {
        return Helper::shell();
    }

    protected function wrapper()
    {
        return Helper::wrapper();
    }

    protected function observer()
    {
        return ServicesContainer::resolve(ShellHistoryObserver::class);
    }

    public function testGetObserverInstances_shouldReturnExpectedArray()
    {
        //
        // @expected
        //
        $expectedInstances = [
            ShellHistoryObserver::class => static::observer(),
        ];

        $shell = static::shell();
        $instances = $shell->getObserverInstances();

        $this->assertEquals($expectedInstances, $instances);
    }

    public function testGetObserverMembers_shouldReturnExpectedArray()
    {
        //
        // @expected
        //
        $expected = [
            ShellHistoryObserver::class,
        ];

        $shell = static::shell();
        $members = $shell->getObserverMembers();

        $this->assertEquals($expected, $members);
    }

    public function testGetObserver_withValidMember_shouldReturnExpectedObject()
    {
        //
        // @expected
        //
        $expected = static::observer();

        $shell = static::shell();
        $instances = $shell->getObserver(ShellhistoryObserver::class);

        $this->assertEquals($expected, $instances);
    }

    public function testHasObserver_withValidMember_shouldReturnTrue()
    {
        $shell = static::shell();
        $result = $shell->hasObserver(ShellhistoryObserver::class);

        $this->assertTrue($result);
    }

    public function testSetObserver_withValidMember_shouldReturnTrue()
    {
        $shell = static::shell();

        ServicesContainer::clean();

        $this->assertFalse($shell->hasObserver(ShellhistoryObserver::class));

        //
        // Initial observer
        //
        $observer = new ShellHistoryObserver;
        $shell->setObserver($observer, ShellhistoryObserver::class);

        $this->assertTrue($shell->hasObserver(ShellhistoryObserver::class));
    }
}
