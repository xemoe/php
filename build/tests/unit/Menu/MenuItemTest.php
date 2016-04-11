<?php

namespace Unit\Menu;

use \PHPUnit_Framework_TestCase as TestCase;
use \Xemoe\Exceptions\InvalidArgumentException;
use \Xemoe\Menu\Contracts\MenuItemContract;
use \Xemoe\Menu\MenuItem;

class MenuItemTest extends TestCase
{
    public function testCreateMenuItem_withMissingRequiredParameter_shouldThrowExpectedException()
    {
        //
        // @expected
        //
        $expectedException = InvalidArgumentException::class;
        $this->setExpectedException($expectedException);

        $menu = new MenuItem([]);
    }

    public function testCreateMenuItem_withInvalidActiveRule_shouldThrowExpectedException()
    {
        //
        // @expected
        //
        $expectedException = InvalidArgumentException::class;
        $this->setExpectedException($expectedException);

        $menu = new MenuItem([
            'name' => 'home',
            'label' => 'Home',
            'link' => '/home',
            'rule' => false,
        ]);
    }

    public function testIsActive_withExpectedUri_shouldReturnTrue()
    {
        //
        // @conditions
        //
        $currentUri = 'http://localhost/home';
        $menu = new MenuItem([
            'name' => 'home',
            'label' => 'Home',
            'link' => '/home',
            'rule' => function(MenuItemContract $item) {
                return (bool) preg_match('#home$#', $item->getUri());
            },
        ]);
        $menu->setUri($currentUri);
        $result = $menu->isActive();

        //
        // @asserts
        //
        $this->assertTrue($result);
    }

    public function testIsActive_withEmptyUri_shouldReturnFalse()
    {
        //
        // @conditions
        //
        $menu = new MenuItem([
            'name' => 'home',
            'label' => 'Home',
            'link' => '/home',
            'rule' => function(MenuItemContract $item) {
                return (bool) preg_match('#home$#', $item->getUri());
            },
        ]);
        $result = $menu->isActive();

        //
        // @asserts
        //
        $this->assertFalse($result);
    }
   
    public function testIsActive_withAnotherUri_shouldReturnFalse()
    {
        //
        // @conditions
        //
        $currentUri = 'http://localhost/another';
        $menu = new MenuItem([
            'name' => 'home',
            'label' => 'Home',
            'link' => '/home',
            'rule' => function(MenuItemContract $item) {
                return (bool) preg_match('#home$#', $item->getUri());
            },
        ]);
        $menu->setUri($currentUri);
        $result = $menu->isActive();

        //
        // @asserts
        //
        $this->assertFalse($result);
    }
   
    public function testToArray_withActive_shouldReturnExpectedArray()
    {
        //
        // @expected
        //
        $expected = [
            'name' => 'home',
            'label' => 'Home',
            'link' => '/home',
            'active' => true,
        ];

        //
        // @conditions
        //
        $currentUri = 'http://localhost/home';
        $menu = new MenuItem([
            'name' => 'home',
            'label' => 'Home',
            'link' => '/home',
            'rule' => function(MenuItemContract $item) {
                return (bool) preg_match('#home$#', $item->getUri());
            },
        ]);
        $menu->setUri($currentUri);
        $result = $menu->toArray();

        //
        // @asserts
        //
        $this->assertEquals($expected, $result);
    }

    public function testToArray_withInactive_shouldReturnExpectedArray()
    {
        //
        // @expected
        //
        $expected = [
            'name' => 'home',
            'label' => 'Home',
            'link' => '/home',
            'active' => false,
        ];

        //
        // @conditions
        //
        $currentUri = 'http://localhost/another';
        $menu = new MenuItem([
            'name' => 'home',
            'label' => 'Home',
            'link' => '/home',
            'rule' => function(MenuItemContract $item) {
                return (bool) preg_match('#home$#', $item->getUri());
            },
        ]);
        $menu->setUri($currentUri);
        $result = $menu->toArray();

        //
        // @asserts
        //
        $this->assertEquals($expected, $result);
    }
}
