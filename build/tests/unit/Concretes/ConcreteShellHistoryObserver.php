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
    protected $paginateCommandHistory = [];
    protected $paginateErrorHistory = [];
    protected $paginateCounter = 0;

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

    public function onErrorExec(ShellContract $shell, $caller)
    {
        array_push(
            $this->execErrorHistory, [
                'error' => trim($shell->getError()),
                'line' => $caller['line'],
                'file' => $caller['file'],
            ]
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

    public function onErrorGetResult(ShellContract $shell, $caller)
    {
        array_push(
            $this->resultErrorHistory, [
                'error' => trim($shell->getError()),
                'line' => $caller['line'],
                'file' => $caller['file'],
            ]
        );
    }

    public function beforeGetPaginate(ShellContract $shell)
    {
        array_push(
            $this->paginateCommandHistory, 
            trim($shell->toString())
        );
    }

    public function afterGetPaginate(ShellContract $shell)
    {
        $this->paginateCounter += 1;
    }

    public function onErrorGetPaginate(ShellContract $shell, $caller)
    {
        array_push(
            $this->paginateErrorHistory, [
                'error' => trim($shell->getError()),
                'line' => $caller['line'],
                'file' => $caller['file'],
            ]
        );
    }

    //
    // New implements
    //
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

    //
    // from getPaginate() command
    //
    public function getPaginateCommandHistory()
    {
        return $this->paginateCommandHistory;
    }

    public function getPaginateErrorHistory()
    {
        return $this->paginateErrorHistory;
    }

    public function getPaginateCounter()
    {
        return $this->paginateCounter;
    }
}
