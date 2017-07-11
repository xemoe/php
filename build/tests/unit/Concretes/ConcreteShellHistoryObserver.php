<?php

namespace Unit\Concretes;

use Xemoe\Contracts\ShellObserverContract;
use Xemoe\Contracts\ShellContract;

class ConcreteShellHistoryObserver implements ShellObserverContract
{
    protected $execCommandHistory = [];
    protected $execErrorHistory = [];
    protected $execCounter = 0;
    protected $resultCommandHistory = [];
    protected $resultErrorHistory = [];
    protected $resultCounter = 0;

    //
    // Implements required
    //
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

    public function beforeGetResult(ShellContract $shell)
    {
        array_push(
            $this->resultCommandHistory, 
            trim($shell->toString())
        );
    }

    public function afterGetResult(ShellContract $shell, $out)
    {
        $this->resultCounter += 1;
    }

    public function onErrorGetResult(ShellContract $shell)
    {
        array_push(
            $this->resultErrorHistory, 
            trim($shell->getError())
        );
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

    //
    // New implements
    //
    // from exec() command
    //
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

    //
    // from getResult() command
    //
    public function getResultCommandHistory()
    {
        return $this->resultCommandHistory;
    }

    public function getResultErrorHistory()
    {
        return $this->resultErrorHistory;
    }

    public function getResultCounter()
    {
        return $this->resultCounter;
    }
}
