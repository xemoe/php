<?php

namespace Unit\Abstracts;

use Unit\Concretes\ConcreteShell as Shell;
use Unit\Concretes\ConcreteWrapper as Wrapper;

class Helper
{
    public static function shell()
    {
        //
        // Command template
        //
        $template = ['echo "%s\n%s\n%s\n%s\n%s"', 'one,two,three,four,five'];

        //
        // Command parameters
        //
        $param = ['one' => 1, 'two' => 2, 'three' => 3, 'four' => 4, 'five' => 5];

        //
        // Anonymous output function
        //
        $closure = function($out) {

            $arr = explode(PHP_EOL, $out);
            $arr = array_filter($arr);

            return ['result' => $arr];
        };

        //
        // Anonymous convert result row function
        //
        $convert = function(&$item) {
            $item = (int) $item + 1;
        };

        return new Shell($template, $param, $closure, $convert);
    }

    public static function wrapper()
    {
        return new Wrapper;
    }
}
