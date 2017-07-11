<?php

namespace Unit\Abstracts;

use PHPUnit_Framework_TestCase as TestCase;
use Xemoe\Contracts\WrapperContract;
use Xemoe\Exceptions\ShellErrorException;
use Xemoe\ServicesContainer;
use Unit\Concretes\ConcreteShell as Shell;
use Unit\Concretes\ConcreteWrapper as Wrapper;
use Unit\Concretes\ConcreteShellHistoryObserver as ShellHistoryObserver;

class ShellHistoryObserverTest extends TestCase
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

    public function testGetExecCommandHistory_shouldReturnExpectedArray()
    {
        //
        // shell #1
        //
        $shell1 = static::shell();

        //
        // shell #2
        //
        $template = ['ls %s', 'dir'];
        $param = ['dir' => '/tmp'];
        $shell2 = new Shell($template, $param);

        //
        // @expected
        //
        $expected = [
            $shell1->toString(),
            $shell2->toString(),
        ];

        $shell1->exec();
        $shell2->exec();

        //
        // get exec history from both shell#1 and shell#2
        //
        $observer = static::observer();
        $history = $observer->getExecCommandHistory();

        $this->assertEquals($expected, $history);
    }

    public function testGetExecErrorHistory_onHasError_shouldReturnExpectedValue()
    {
        //
        // @expected
        //
        $expected = [[
            'error' => 'ls: cannot access /foo: No such file or directory',
            'file' => __FILE__,
        ]];

        try {
            //
            // use `ls` command on not existing directory
            //
            $template = ['ls %s', 'dir'];
            $param = ['dir' => '/foo'];
            $shell = new Shell($template, $param);
            $expected[0]['line'] = __LINE__ + 1;
            $shell->exec();
        } catch (ShellErrorException $e) {
            $observer = static::observer();
            $errors = $observer->getExecErrorHistory();
        }

        $this->assertEquals($expected, $errors);
    }

    public function testGetExecCounter_shouldReturnExpectedValue()
    {
        //
        // shell #1
        //
        $shell1 = static::shell();

        //
        // shell #2
        //
        $template = ['ls %s', 'dir'];
        $param = ['dir' => '/tmp'];
        $shell2 = new Shell($template, $param);

        //
        // @expected
        //
        $expected = 2;

        $shell1->exec();
        $shell2->exec();

        //
        // get exec history from both shell#1 and shell#2
        //
        $observer = static::observer();
        $counter = $observer->getExecCounter();

        $this->assertEquals($expected, $counter);
    }

    public function testGetExecCounter_onError_shouldReturnExpectedValue()
    {
        //
        // @expected
        //
        $expected = 0;

        try {
            //
            // use `ls` command on not existing directory
            //
            $template = ['ls %s', 'dir'];
            $param = ['dir' => '/foo'];
            $shell = new Shell($template, $param);
            $shell->exec();
        } catch (ShellErrorException $e) {
            $observer = static::observer();
            $counter = $observer->getExecCounter();
        }

        $this->assertEquals($expected, $counter);
    }

    public function testGetResultCommandHistory_shouldReturnExpectedArray()
    {
        //
        // shell #1
        //
        $shell1 = static::shell();

        //
        // shell #2
        //
        $template = ['ls %s', 'dir'];
        $param = ['dir' => '/tmp'];
        $shell2 = new Shell($template, $param);

        //
        // @expected
        //
        $expected = [
            $shell1->toString(),
            $shell2->toString(),
        ];

        $shell1->getResult();
        $shell2->getResult();

        //
        // get exec history from both shell#1 and shell#2
        //
        $observer = static::observer();
        $history = $observer->getResultCommandHistory();

        $this->assertEquals($expected, $history);
    }

    public function testGetResultErrorHistory_onHasError_shouldReturnExpectedValue()
    {
        //
        // @expected
        //
        $expected = [[
            'error' => 'ls: cannot access /foo: No such file or directory',
            'file' => __FILE__,
        ]];

        try {
            //
            // use `ls` command on not existing directory
            //
            $template = ['ls %s', 'dir'];
            $param = ['dir' => '/foo'];
            $shell = new Shell($template, $param);
            $expected[0]['line'] = __LINE__ + 1;
            $shell->getResult();
        } catch (ShellErrorException $e) {
            $observer = static::observer();
            $errors = $observer->getResultErrorHistory();
        }

        $this->assertEquals($expected, $errors);
    }

    public function testGetResultCounter_shouldReturnExpectedValue()
    {
        //
        // shell #1
        //
        $shell1 = static::shell();

        //
        // shell #2
        //
        $template = ['ls %s', 'dir'];
        $param = ['dir' => '/tmp'];
        $shell2 = new Shell($template, $param);

        //
        // @expected
        //
        $expected = 2;

        $shell1->getResult();
        $shell2->getResult();

        //
        // get exec history from both shell#1 and shell#2
        //
        $observer = static::observer();
        $counter = $observer->getResultCounter();

        $this->assertEquals($expected, $counter);
    }

    public function testGetResultCounter_onError_shouldReturnExpectedValue()
    {
        //
        // @expected
        //
        $expected = 0;

        try {
            //
            // use `ls` command on not existing directory
            //
            $template = ['ls %s', 'dir'];
            $param = ['dir' => '/foo'];
            $shell = new Shell($template, $param);
            $shell->getResult();
        } catch (ShellErrorException $e) {
            $observer = static::observer();
            $counter = $observer->getResultCounter();
        }

        $this->assertEquals($expected, $counter);
    }

    public function testGetPaginateCommandHistory_shouldReturnExpectedArray()
    {
        //
        // shell #1
        //
        $shell1 = static::shell();

        //
        // @expected
        //
        $expected = [
            $shell1->toString(),
        ];

        $shell1->getPaginate();

        //
        // get exec history from both shell#1 and shell#2
        //
        $observer = static::observer();
        $history = $observer->getPaginateCommandHistory();

        $this->assertEquals($expected, $history);
    }

    public function testGetPaginateErrorHistory_onHasError_shouldReturnExpectedValue()
    {
        //
        // @expected
        //
        $expected = [[
            'error' => 'ls: cannot access /foo: No such file or directory',
            'file' => __FILE__,
        ]];

        try {
            //
            // use `ls` command on not existing directory
            //
            $template = ['ls %s', 'dir'];
            $param = ['dir' => '/foo'];
            $shell = new Shell($template, $param);
            $expected[0]['line'] = __LINE__ + 1;
            $shell->getPaginate();
        } catch (ShellErrorException $e) {
            $observer = static::observer();
            $errors = $observer->getPaginateErrorHistory();
        }

        $this->assertEquals($expected, $errors);
    }

    public function testGetPaginateCounter_shouldReturnExpectedValue()
    {
        //
        // shell #1
        //
        $shell1 = static::shell();

        //
        // @expected
        //
        $expected = 2;

        $shell1->getPaginate(1);
        $shell1->getPaginate(2);

        //
        // get exec history from both shell#1 and shell#2
        //
        $observer = static::observer();
        $counter = $observer->getPaginateCounter();

        $this->assertEquals($expected, $counter);
    }

    public function testGetPaginateCounter_onError_shouldReturnExpectedValue()
    {
        //
        // @expected
        //
        $expected = 0;

        try {
            //
            // use `ls` command on not existing directory
            //
            $template = ['ls %s', 'dir'];
            $param = ['dir' => '/foo'];
            $shell = new Shell($template, $param);
            $shell->getPaginate();
        } catch (ShellErrorException $e) {
            $observer = static::observer();
            $counter = $observer->getPaginateCounter();
        }

        $this->assertEquals($expected, $counter);
    }
}
