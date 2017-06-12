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
    /**
    public function beforeExec($w);
    public function afterExec($w);
    public function onErrorExec($w);
    public function beforeGetResult($w);
    public function afterGetResult($w, $out);
    public function onErrorGetResult($w);
    public function beforeGetPaginate($w);
    public function afterGetPaginate($w);
    public function onErrorGetPaginate($w);
    **/
}
