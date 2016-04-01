<?php

namespace Xemoe\Contracts;

interface ResolvableContract
{
    public function attachable(array $classes);
    public static function attach($object);
    public static function detach($classname);
    public static function resolve($classname);
    public static function has($classname);
    public static function clean();
}
