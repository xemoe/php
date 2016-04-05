<?php

namespace Unit\Containers;

use \PHPUnit_Framework_TestCase as TestCase;
use \Xemoe\Exceptions\InvalidArgumentException;
use \Xemoe\Exceptions\UnresolvableException;
use \Xemoe\ConfigurationContainer;

class ConfigurationContainerTest extends TestCase
{
    public function setUp()
    {
        ConfigurationContainer::clean();
    }

    public function testSet_withEmptyAcceptsArray_shouldThrowExpectedException()
    {
        //
        // @expected
        //
        $expectedException = InvalidArgumentException::class;

        $this->setExpectedException($expectedException);

        $emptyAccepts = [];
        $i = ConfigurationContainer::getInstance();
        $i->attachable($emptyAccepts);
    }

    public function testSet_withNotExistingAcceptsProperties_shouldThrowExpectedException()
    {
        //
        // @expected
        //
        $expectedException = InvalidArgumentException::class;

        $this->setExpectedException($expectedException);

        $accepts = [
            'FOO',
        ];
        $i = ConfigurationContainer::getInstance();
        $i->attachable($accepts);

        $i->set('BAR', 'BAR');
    }

    public function testGet_withAttachableProperties_shouldReturnExpectValue()
    {
        //
        // @expected
        //
        $expected = 'FOO';

        //
        // Defined attachable contracts
        //
        $accepts = [
            'FOO',
        ];
        $i = ConfigurationContainer::getInstance();
        $i->attachable($accepts);
        $i->set('FOO', 'FOO');

        $result = ConfigurationContainer::getInstance()->get('FOO');

        //
        // @asserts
        //
        $this->assertEquals($expected, $result);
    }

    public function testGet_withNotExistingAcceptsContract_shouldThrowExpectedException()
    {
        //
        // @expected
        //
        $expectedException = UnresolvableException::class;

        $this->setExpectedException($expectedException);

        $accepts = [
            'FOO',
        ];
        $i = ConfigurationContainer::getInstance();
        $i->attachable($accepts);
        $i->set('FOO', 'FOO');

        $result = ConfigurationContainer::getInstance()->get('BAR');
    }

    public function testHas_withExistingProperties_shouldReturnTrue()
    {
        $accepts = [
            'FOO',
        ];
        $i = ConfigurationContainer::getInstance();
        $i->attachable($accepts);
        $i->set('FOO', 'FOO');

        $this->assertTrue(ConfigurationContainer::has('FOO'));
    }

    public function testHas_withNotExistingProperties_shouldReturnFalse()
    {
        $accepts = [
            'FOO',
        ];
        $i = ConfigurationContainer::getInstance();
        $i->attachable($accepts);
        $i->set('FOO', 'FOO');

        $this->assertFalse(ConfigurationContainer::has('BAR'));
    }
}
