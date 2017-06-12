<?php

namespace Unit\Concretes;

use Xemoe\Abstracts\AbstractShell;
use Xemoe\Contracts\ObservableContract;
use Xemoe\Contracts\ShellObserverContract;
use Xemoe\Contracts\WrapperContract;
use Xemoe\ServicesContainer;
use Unit\Concretes\ConcreteWrapper as Wrapper;
use Unit\Concretes\ConcreteShellHistoryObserver as ShellHistoryObserver;

class ConcreteShell extends AbstractShell implements ObservableContract
{
    protected $observerInstances = [];
    protected $observerMembers = [];
    protected $wrapper;

    //
    // @abstract
    //
    public function getError()
    {
        return $this->wrapper->getError();
    }

    public function getObserverMembers()
    {
        return [
            ShellHistoryObserver::class,
        ];
    }

    public function getObserverInstances()
    {
        $members = static::getObserverMembers();
        $instances = $this->observerInstances;

        foreach ($members as $klass) {
            //
            // Resolve member from ServicesContainer if not exist
            //
            if (!isset($instances[$klass])) {
                if (ServicesContainer::has($klass)) {
                    $instances[$klass] = ServicesContainer::resolve($klass);
                }
            }
        }

        return $instances;
    }

    public function getObserver($klass)
    {
        $instances = static::getObserverInstances();
        return $instances[$klass];
    }

    public function hasObserver($klass)
    {
        $instances = static::getObserverInstances();
        return isset($instances[$klass]);
    }

    public function setObserver($observer, $klass)
    {
        if ($observer instanceof ShellObserverContract) {
            $this->observerInstances[$klass] = $observer;
            return true;
        }

        return false;
    }

    //
    // @abstract
    //
    protected function getWrapper()
    {
        if ($this->wrapper instanceof Wrapper) {
            return $this->wrapper;
        }

        if (ServicesContainer::has(Wrapper::class)) {
            $this->wrapper = ServicesContainer::resolve(Wrapper::class);
        } else {
            $this->wrapper = new Wrapper;
        }

        return $this->wrapper;
    }
}
