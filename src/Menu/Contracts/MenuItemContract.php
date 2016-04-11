<?php

namespace Xemoe\Menu\Contracts;

use \Xemoe\Contracts\StringTemplateContract;

interface MenuItemContract
{
    public function getName();
    public function getLabel();
    public function getLink();
    public function setActiveRule($callable);
    public function getActiveRule();
    public function setUri($uri);
    public function getUri();
    public function isActive();
    public function setTemplate(StringTemplateContract $template);
    public function getTemplate();
    public function toArray();
}
