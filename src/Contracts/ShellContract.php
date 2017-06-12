<?php

namespace Xemoe\Contracts;

interface ShellContract
{
    public function __construct(array $template, array $args, $parser = false, $convert = false);
    public function toString();
    public function exec();
    public function getResult();
    public function getPaginate($page = 1, $perpage = 10, $total = false);
    public function getError();
}
