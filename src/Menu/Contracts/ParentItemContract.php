<?php

namespace Xemoe\Menu\Contracts;

use \Xemoe\Contracts\StringTemplateContract;

interface ParentItemContract
{
    public function addChild(MenuItemContract $item);
    public function getChild();
    public function hasActiveChild();
}
