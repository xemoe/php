<?php

namespace Xemoe\Contracts;

interface ShellObserverContract
{
    public function beforeExec(ShellContract $w);
    public function afterExec(ShellContract $w);
    public function onErrorExec(ShellContract $w);
    public function beforeGetResult(ShellContract $w);
    public function afterGetResult(ShellContract $w, $out);
    public function onErrorGetResult(ShellContract $w);
    public function beforeGetPaginate(ShellContract $w);
    public function afterGetPaginate(ShellContract $w);
    public function onErrorGetPaginate(ShellContract $w);
}
