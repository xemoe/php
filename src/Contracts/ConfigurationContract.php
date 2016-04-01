<?php

namespace Xemoe\Contracts;

interface ConfigurationContract
{
    public function attachable(array $classes);
    public static function set($name, $value);
    public static function get($name);
    public static function has($name);
    public static function clean();
}
