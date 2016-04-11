<?php

namespace Unit\Container;

use \RuntimeException;
use \PHPUnit_Framework_TestCase as TestCase;
use \Xemoe\Exceptions\InvalidArgumentException;
use \Xemoe\Exceptions\UnresolvableException;
use \Xemoe\MenuContainer;
use \Xemoe\Menu\Contracts\MenuItemContract;
use \Xemoe\Menu\MenuItem;
use \Xemoe\Menu\ParentItem;

class MenuContainerTest extends TestCase
{
    public function setUp()
    {
        MenuContainer::clean();
    }

    protected function dashboardMenu()
    {
        $child = new MenuItem([
            'name' => 'dashboard.today',
            'label' => 'Today',
            'link' => 'dashboard/today',
            'rule' => function(MenuItemContract $item) {
                return true;
            },
        ]);

        $parent = new ParentItem([
            'name' => 'dashboard',
            'label' => 'Dashboard',
            'link' => '#',
        ]);

        $parent->addChild($child);

        return $parent;
    }

    protected function userMenu()
    {
        $showUserMenu = new MenuItem([
            'name' => 'user.show',
            'label' => 'Show user',
            'link' => 'user/show',
            'rule' => function(MenuItemContract $item) {
                return false;
            },
        ]);

        $createUserMenu = new MenuItem([
            'name' => 'user.create',
            'label' => 'Create new user',
            'link' => 'user/create',
            'rule' => function(MenuItemContract $item) {
                return false;
            },
        ]);

        $parent = new ParentItem([
            'name' => 'user',
            'label' => 'User',
            'link' => '#',
        ]);

        $parent->addChild($showUserMenu);
        $parent->addChild($createUserMenu);

        return $parent;
    }

    public function testGetMenuArray_withEmptyMenu_shouldReturnExpectedArray()
    {
        //
        // @expected
        //
        $expected = [];

        //
        // @conditions
        //
        $currentUri = 'http://localhost/';
        $i = MenuContainer::getInstance();
        $result = $i->getMenuArray($currentUri);

        //
        // @asserts
        //
        $this->assertEquals($expected, $result);
    }

    public function testGetMenuArray_withAddmenu_shouldReturnExpectedArray()
    {
        //
        // @expected
        //
        $expected = [
            'dashboard' => ['name' => 'dashboard', 'label' => 'Dashboard', 'active' => true, 'link' => '#', 'child' => [
                'dashboard.today' => ['name' => 'dashboard.today', 'label' => 'Today', 'active' => true, 'link' => 'dashboard/today',],
            ]],
            'user' => ['name' => 'user', 'label' => 'User', 'active' => false, 'link' => '#', 'child' => [
                'user.show' => ['name' => 'user.show', 'label' => 'Show user', 'active' => false, 'link' => 'user/show',],
                'user.create' => ['name' => 'user.create', 'label' => 'Create new user', 'active' => false, 'link' => 'user/create',],
            ]],
        ];

        //
        // @conditions
        //
        $currentUri = 'http://localhost/dashboard/today';

        MenuContainer::clean();
        MenuContainer::addMenu(static::dashboardMenu());
        MenuContainer::addMenu(static::userMenu());

        $result = MenuContainer::getMenuArray($currentUri);

        //
        // @asserts
        //
        $this->assertEquals($expected, $result);
    }
}
