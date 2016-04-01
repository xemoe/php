<?php

namespace Xemoe\Contracts;

interface ServicesContract
{
    public function attachable(array $classes);
    public static function attach($object, $alias = null);
    public static function detach($classname);
    public static function resolve($classname);
    public static function has($classname);
    public static function clean();
}
