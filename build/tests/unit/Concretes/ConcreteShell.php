<?php

namespace Unit\Concretes;

use \Xemoe\Abstracts\AbstractShell;
use \Xemoe\Contracts\WrapperContract;
use \Xemoe\ServicesContainer;
use \Unit\Concretes\ConcreteWrapper;

class ConcreteShell extends AbstractShell
{
    protected function getWrapper()
    {
        if (ServicesContainer::has(ConcreteWrapper::class)) {
            $wrapper = ServicesContainer::resolve(ConcreteWrapper::class);
        } else {
            $wrapper = new ConcreteWrapper;
        }

        return $wrapper;
    }
}
