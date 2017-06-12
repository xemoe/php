<?php

namespace Unit\Abstracts;

use PHPUnit_Framework_TestCase as TestCase;
use Xemoe\Contracts\WrapperContract;
use Xemoe\Exceptions\ShellErrorException;
use Xemoe\ServicesContainer;
use Unit\Concretes\ConcreteShell as Shell;
use Unit\Concretes\ConcreteWrapper as Wrapper;

class ShellTest extends TestCase
{
    public function setUp()
    {
        ServicesContainer::clean();
    }

    protected function shell()
    {
        return Helper::shell();
    }

    protected function wrapper()
    {
        return Helper::wrapper();
    }

    protected function stubWrapper()
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

    public function testStubWrapperExec_withFoo_shouldReturnExpectValue()
    {
        //
        // @expected
        //
        $expected= 'bar';

        $wrapper = static::stubWrapper();
        $result = $wrapper->exec(['foo'], []);

        $this->assertEquals($expected, $result);
    }

    public function testCreateWrapperShell_shouldReturnObject()
    {
        //
        // @expected
        //
        $expected = 'object';

        $shell = new Shell([], [], false);

        $this->assertInternalType($expected, $shell);
    }

    public function testGetResult_shouldReturnExpectedArray()
    {
        //
        // @expected
        //
        $expected = [2,3,4,5,6];

        $this->assertEquals($expected, static::shell()->getResult());
    }

    public function testGetResult_withStubWrapper_shouldReturnExpectValue()
    {
        //
        // @expected
        //
        $expected = 'foo => bar';

        //
        // Attach stub wrapper
        // into ServicesContrainer
        // with WrapperContract as alias
        //
        $wrapper = static::stubWrapper();
        ServicesContainer::attach($wrapper, Wrapper::class);

        $template = ['foo'];
        $closure = function($out) {
            return ['result' => 'foo => ' . $out];
        };

        $shell = new Shell($template, [], $closure);
        $result = $shell->getResult();

        $this->assertEquals($expected, $result);
    }

    public function testGetResult_withStubWrapper_withDefaultClosure_shouldReturnExpectValue()
    {
        //
        // @expected
        //
        $expected = 'bar';

        //
        // Attach stub wrapper
        // into ServicesContrainer
        // with WrapperContract as alias
        //
        $wrapper = static::stubWrapper();
        ServicesContainer::attach($wrapper, Wrapper::class);

        $template = ['foo'];

        $shell = new Shell($template, []);
        $result = $shell->getResult();

        $this->assertEquals($expected, $result);
    }

    public function testGetPaginate_withDefaultParameters_shouldReturnExpectedArray()
    {
        $page = 1;
        $perpage = 10;
        $total = 5;

        //
        // @expected
        //
        $expected = [
            'items' => [2,3,4,5,6],
            'paging' => static::wrapper()->paginate($total, $page, $perpage),
        ];

        $this->assertEquals($expected, static::shell()->getPaginate());
    }
    
    public function testGetPaginate_withPage1_shouldReturnExpectedArray()
    {
        $page = 1;
        $perpage = 3;
        $total = 5;

        //
        // @expected
        //
        $expected = [
            'items' => [4,5,6],
            'paging' => static::wrapper()->paginate($total, $page, $perpage),
        ];

        $this->assertEquals($expected, static::shell()->getPaginate($page, $perpage, $total));
    }

    public function testGetPaginate_withPage2_shouldReturnExpectedArray()
    {
        $page = 2;
        $perpage = 3;
        $total = 5;

        //
        // @expected
        //
        $expected = [
            'items' => [2,3],
            'paging' => static::wrapper()->paginate($total, $page, $perpage),
        ];

        $this->assertEquals($expected, static::shell()->getPaginate($page, $perpage, $total));
    }

    public function testToString_shouldReturnExpectedValue()
    {
        //
        // @expected
        //
        $expected = 'echo "1\n2\n3\n4\n5"';

        $this->assertEquals($expected, static::shell()->toString());
    }

    public function testGetError_onHasError_shouldReturnExpectedValue()
    {
        //
        // @expected
        //
        $expectedException = ShellErrorException::class;
        $expectedError = 'ls: cannot access /foo: No such file or directory';

        $this->setExpectedException($expectedException);

        $template = ['ls %s', 'dir'];
        $param = ['dir' => '/foo'];
        $shell = new Shell($template, $param);

        $out = $shell->getResult();
        $error = trim($shell->getError());

        $this->assertEquals($expectedError, $error);
    }

    public function testGetError_onEmptyError_shouldReturnExpectedValue()
    {
        //
        // @expected
        //
        $expectedError = '';

        $template = ['ls %s', 'dir'];
        $param = ['dir' => '/tmp'];
        $shell = new Shell($template, $param);

        $out = $shell->getResult();
        $error = trim($shell->getError());

        $this->assertEquals($expectedError, $error);
    }
}
