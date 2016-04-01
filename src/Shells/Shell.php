<?php

namespace Xemoe\Shells;

use \Xemoe\Abstracts\AbstractShell;
use \Xemoe\Contracts\WrapperContract;
use \Xemoe\ServicesContainer;
use \Xemoe\Wrappers\Wrapper;

class Shell extends AbstractShell
{
    protected function getWrapper()
    {
        if (ServicesContainer::has(WrapperContract::class)) {
            $wrapper = ServicesContainer::resolve(WrapperContract::class);
        } else {
            $wrapper = new Wrapper;
        }

        return $wrapper;
    }
}
