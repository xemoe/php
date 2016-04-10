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
        $expectedException = InvalidArgumentException::class;
        $this->setExpectedException($expectedException);

        $menu = new MenuItem([]);
    }

    public function testCreateMenuItem_withInvalidActiveRule_shouldThrowExpectedException()
    {
        $expectedException = InvalidArgumentException::class;
        $this->setExpectedException($expectedException);

        $menu = new MenuItem([
            'name' => 'home',
            'label' => 'Home',
            'link' => '/home',
            'active' => false,
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
            'active' => function(MenuItemContract $item) {
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
            'active' => function(MenuItemContract $item) {
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
}
