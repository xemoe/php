<?php

namespace Xemoe\Contracts;

use \Xemoe\Menu\Contracts\ParentItemContract;

interface MenuContainerContract
{
    public static function addMenu(ParentItemContract $parent);
    public static function getMenuArray($uri);
    public static function clean();
}
