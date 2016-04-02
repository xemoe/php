<?php

namespace Unit\Shells;

use \PHPUnit_Framework_TestCase as TestCase;
use \Unit\ConcreteShell as Shell;
use \Unit\ConcreteWrapper as Wrapper;
use \Xemoe\ServicesContainer;
use \Xemoe\Contracts\WrapperContract;

class ShellTest extends TestCase
{
    public function setUp()
    {
        ServicesContainer::clean();
    }

    protected function shell()
    {
        //
        // Command template
        //
        $template = ['echo "%s\n%s\n%s\n%s\n%s"', 'one,two,three,four,five'];

        //
        // Command parameters
        //
        $param = ['one' => 1, 'two' => 2, 'three' => 3, 'four' => 4, 'five' => 5];

        //
        // Anonymous output function
        //
        $closure = function($out) {

            $arr = explode(PHP_EOL, $out);
            $arr = array_filter($arr);

            return ['result' => $arr];
        };

        //
        // Anonymous convert result row function
        //
        $convert = function(&$item) {
            $item = (int) $item + 1;
        };

        return new Shell($template, $param, $closure, $convert);
    }

    protected function wrapper()
    {
        return new Wrapper;
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
        $expected= 'bar';

        $wrapper = static::stubWrapper();
        $result = $wrapper->exec(['foo'], []);

        $this->assertEquals($expected, $result);
    }

    public function testCreateWrapperShell_shouldReturnObject()
    {
        $shell = new Shell([], [], false);

        $this->assertInternalType('object', $shell);
    }

    public function testGetResult_shouldReturnExpectedArray()
    {
        $expected = [2,3,4,5,6];

        $this->assertEquals($expected, static::shell()->getResult());
    }

    public function testGetResult_withStubWrapper_shouldReturnExpectValue()
    {
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

        $expected = [
            'items' => [2,3],
            'paging' => static::wrapper()->paginate($total, $page, $perpage),
        ];

        $this->assertEquals($expected, static::shell()->getPaginate($page, $perpage, $total));
    }

    public function testToString_shouldReturnExpectedValue()
    {
        $expected = sprintf('echo "1\n2\n3\n4\n5"');

        $this->assertEquals($expected, static::shell()->toString());
    }
}
