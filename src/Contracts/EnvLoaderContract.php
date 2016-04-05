<?php

namespace Xemoe\Contracts;

interface EnvLoaderContract
{
    public static function loadEnv($file);
    public static function getEnv($name);
}
