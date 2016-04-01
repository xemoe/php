<?php

namespace Xemoe\Contracts;

interface WrapperContract
{
    public function paging($paginator);
    public function exec(array $templates, array $vars = []);
}
