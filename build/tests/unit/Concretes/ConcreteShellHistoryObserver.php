<?php

namespace Unit\Concretes;

use Xemoe\Contracts\ShellObserverContract;
use Xemoe\Contracts\ShellContract;

class ConcreteShellHistoryObserver implements ShellObserverContract
{
    protected $execCommandHistory = [];
    protected $execErrorHistory = [];
    protected $execCounter = 0;

    public function beforeExec(ShellContract $shell)
    {
        array_push(
            $this->execCommandHistory, 
            trim($shell->toString())
        );
    }

    public function afterExec(ShellContract $shell)
    {
        $this->execCounter += 1;
    }

    public function onErrorExec(ShellContract $shell)
    {
        array_push(
            $this->execErrorHistory, 
            trim($shell->getError())
        );
    }

    public function getExecCommandHistory()
    {
        return $this->execCommandHistory;
    }

    public function getExecErrorHistory()
    {
        return $this->execErrorHistory;
    }

    public function getExecCounter()
    {
        return $this->execCounter;
    }

    public function beforeGetResult(ShellContract $shell)
    {
    }

    public function afterGetResult(ShellContract $shell, $out)
    {
    }

    public function onErrorGetResult(ShellContract $shell)
    {
    }

    public function beforeGetPaginate(ShellContract $shell)
    {
    }

    public function afterGetPaginate(ShellContract $shell)
    {
    }

    public function onErrorGetPaginate(ShellContract $shell)
    {
    }
}
