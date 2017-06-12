<?php

namespace Unit\Concretes;

use Xemoe\Abstracts\AbstractWrapper;

class ConcreteWrapper extends AbstractWrapper
{
    protected $out;
    protected $error;

    //
    // @abstract
    //
    public function getError()
    {
        return $this->error;
    }
}
