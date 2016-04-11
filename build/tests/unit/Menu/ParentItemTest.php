<?php

namespace Unit\Menu;

use \PHPUnit_Framework_TestCase as TestCase;
use \Xemoe\Exceptions\InvalidArgumentException;
use \Xemoe\Menu\Contracts\MenuItemContract;
use \Xemoe\Menu\MenuItem;
use \Xemoe\Menu\ParentItem;

class ParentItemTest extends TestCase
{
    public function testHasActiveChild_withActiveChild_shouldReturnTrue()
    {
        //
        // @conditions
        //
        $currentUri = 'http://localhost/dashboard/today';
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
        $parent->setUri($currentUri);

        $result = $parent->hasActiveChild();

        //
        // @asserts
        //
        $this->assertTrue($result);
    }

    public function testHasActiveChild_withInactiveChild_shouldReturnFalse()
    {
        //
        // @conditions
        //
        $currentUri = 'http://localhost/other';
        $child = new MenuItem([
            'name' => 'dashboard.today',
            'label' => 'Today',
            'link' => 'dashboard/today',
            'rule' => function(MenuItemContract $item) {
                return false;
            },
        ]);

        $parent = new ParentItem([
            'name' => 'dashboard',
            'label' => 'Dashboard',
            'link' => '#',
        ]);
        $parent->addChild($child);
        $parent->setUri($currentUri);

        $result = $parent->hasActiveChild();

        //
        // @asserts
        //
        $this->assertFalse($result);
    }

    public function testToArray_withActiveChild_shouldReturnExpectedArray()
    {
        //
        // @expected
        //
        $expected = [
            'dashboard' => ['name' => 'dashboard', 'label' => 'Dashboard', 'active' => true, 'link' => '#', 'child' => [
                'dashboard.today' => ['name' => 'dashboard.today', 'label' => 'Today', 'active' => true, 'link' => 'dashboard/today',],
            ]],
        ];

        //
        // @conditions
        //
        $currentUri = 'http://localhost/dashboard/today';
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
        $parent->setUri($currentUri);

        $result = $parent->toArray();

        //
        // @asserts
        //
        $this->assertEquals($expected, $result);
    }

    public function testToArray_withoutChild_shouldReturnExpectedArray()
    {
        //
        // @expected
        //
        $expected = [
            'dashboard' => ['name' => 'dashboard', 'label' => 'Dashboard', 'active' => true, 'link' => 'dashboard/today'],
        ];

        //
        // @conditions
        //
        $currentUri = 'http://localhost/dashboard/today';
        $parent = new ParentItem([
            'name' => 'dashboard',
            'label' => 'Dashboard',
            'link' => 'dashboard/today',
        ]);
        $parent->setActiveRule(
            function(MenuItemContract $item) {
                return true;
            }
        );
        $parent->setUri($currentUri);

        $result = $parent->toArray();

        //
        // @asserts
        //
        $this->assertEquals($expected, $result);
    }
}
