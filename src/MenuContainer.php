<?php

namespace Xemoe;

use \Xemoe\Container\Traits\SingletonTrait;
use \Xemoe\Contracts\MenuContainerContract;
use \Xemoe\Menu\Contracts\ParentItemContract;

class MenuContainer implements MenuContainerContract
{
    use SingletonTrait;

    protected static $menu;

    protected function __construct()
    {
        static::clean();
    }

    public static function addMenu(ParentItemContract $parent)
    {
        static::$menu[$parent->getName()] = $parent;
    }

    public static function getMenuArray($uri)
    {
        $ret = [];

        foreach (static::$menu as $item) {
            $item->setUri($uri);
            $ret = array_merge($ret, $item->toArray());
        }

        return $ret;
    }

    public static function clean()
    {
       static::$menu = []; 
    }
}
