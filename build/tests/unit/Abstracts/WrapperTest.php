<?php

namespace Unit\Abstracts;

use PHPUnit_Framework_TestCase as TestCase;
use Unit\Concretes\ConcreteWrapper as Wrapper;
use Xemoe\Exceptions\ShellErrorException;

class WrapperTest extends TestCase
{
    public function testVariablesTemplate_withoutArgument_shouldReturnString()
    {
        $wrapper = new Wrapper;

        $template = ['foo'];
        $expect  = 'foo';

        $this->assertEquals($expect, $wrapper->variables_template($template));
    }

    public function testVariablesTemplate_withArgument_shouldReturnString()
    {
        $expect  = 'foo = bar';

        $wrapper = new Wrapper;
        $template = ['%s = %s', 'foo1,bar1'];
        $vars = ['foo1' => 'foo', 'bar1' => 'bar'];

        $this->assertEquals($expect, $wrapper->variables_template($template, $vars));
    }

    public function testVariablesTemplate_withReverseArgument_shouldReturnString()
    {
        $expect  = 'foo = bar';

        $wrapper = new Wrapper;
        $template = ['%s = %s', 'foo1,bar1'];
        $vars = ['bar1' => 'bar', 'foo1' => 'foo'];

        $this->assertEquals($expect, $wrapper->variables_template($template, $vars));
    }

    public function testWrapperNumfmt_shouldReturnString()
    {
        $wrapper = new Wrapper;

        $this->assertEquals('1 KB', $wrapper->numfmt(pow(2,10)));
        $this->assertEquals('1 MB', $wrapper->numfmt(pow(2,20)));
        $this->assertEquals('1 GB', $wrapper->numfmt(pow(2,30)));
        $this->assertEquals('0 KB', $wrapper->numfmt(0));
        $this->assertEquals('1 B', $wrapper->numfmt(1));
    }

    public function testWrapperPaginate_withPage1_shouldReturnExpectedArray()
    {
        $expected = [
            'total' => 23,
            'from' => 1,
            'to' => 5,
            'pages' => 5,
            'page' => 1,
            'perpage' => 5,
            'limit' => 5, 
        ];

        $total = 23;
        $page = 1;
        $perpage = 5;

        $wrapper = new Wrapper;

        $this->assertEquals($expected, $wrapper->paginate($total, $page, $perpage));
    }

    public function testWrapperPaginate_withPage2_shouldReturnExpectedArray()
    {
        $expected = [
            'total' => 23,
            'from' => 6,
            'to' => 10,
            'pages' => 5,
            'page' => 2,
            'perpage' => 5,
            'limit' => 5, 
        ];

        $total = 23;
        $page = 2;
        $perpage = 5;

        $wrapper = new Wrapper;

        $this->assertEquals($expected, $wrapper->paginate($total, $page, $perpage));
    }

    public function testWrapperPaginate_withPage5_shouldReturnExpectedArray()
    {
        $expected = [
            'total' => 23,
            'from' => 21,
            'to' => 23,
            'pages' => 5,
            'page' => 5,
            'perpage' => 5,
            'limit' => 3, 
        ];

        $total = 23;
        $page = 5;
        $perpage = 5;

        $wrapper = new Wrapper;

        $this->assertEquals($expected, $wrapper->paginate($total, $page, $perpage));
    }

    public function testWrapperExec_withError_shouldReturnExpectedValue()
    {
        //
        // @expected
        //
        $expectedException = ShellErrorException::class;
        $expectedError = 'ls: cannot access /foo: No such file or directory';

        $this->setExpectedException($expectedException);

        $wrapper = new Wrapper;

        $template = ['ls %s', 'dir'];
        $param = ['dir' => '/foo'];
        $wrapper->exec($template, $param);
        $error = trim($wrapper->getError());

        $this->assertEquals($expectedError, $error);
    }
}
