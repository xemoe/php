<?php

namespace Xemoe\Abstracts;

use Xemoe\Contracts\ShellContract;
use Xemoe\Exceptions\ShellErrorException;
use Exception;

abstract class AbstractShell implements ShellContract 
{
    protected $template;
    protected $args;
    protected $parser;
    protected $convert;
    protected $observer;
    protected $wrapper;

    public function __construct(array $template, array $args, $parser = false, $convert = false)
    {
        $this->template = $template;
        $this->args = $args;
        $this->parser = $parser;
        $this->convert = $convert;
    }

    abstract protected function getWrapper();
    abstract public function getError();
    abstract public function getObserverInstances();

    public function toString()
    {
        return static::getWrapper()->variables_template($this->template, $this->args);
    }

    public function exec()
    {
        $observers = $this->getObserverInstances();
        $wrapper = static::getWrapper();

        //
        // Before exec
        //
        foreach ($observers as $observer) {
            if (is_callable([$observer, 'beforeExec'])) {
                $observer->beforeExec($this);
            }
        }

        try {
            $wrapper->exec($this->template, $this->args);
        } catch (ShellErrorException $e) {

            $bt = debug_backtrace();
            $caller = array_shift($bt);

            foreach ($observers as $observer) {
                if (is_callable([$observer, 'onErrorExec'])) {
                    $observer->onErrorExec($this, $caller);
                }
            }
            throw $e;
        }

        //
        // After exec
        //
        foreach ($observers as $observer) {
            if (is_callable([$observer, 'afterExec'])) {
                $observer->afterExec($this);
            }
        }
    }

    public function getResult()
    {
        $observers = $this->getObserverInstances();
        $wrapper = static::getWrapper();

        //
        // Before getResult
        //
        foreach ($observers as $observer) {
            if (is_callable([$observer, 'beforeGetResult'])) {
                $observer->beforeGetResult($this);
            }
        }

        try {
            $out = $wrapper->exec($this->template, $this->args);
        } catch (ShellErrorException $e) {

            $bt = debug_backtrace();
            $caller = array_shift($bt);

            foreach ($observers as $observer) {
                if (is_callable([$observer, 'onErrorGetResult'])) {
                    $observer->onErrorGetResult($this, $caller);
                }
            }
            throw $e;
        }

        $parser = $this->parser;

        //
        // Return if empty parser
        //
        if (!is_callable($parser)) {

            //
            // After getResult #1
            //
            foreach ($observers as $observer) {
                if (is_callable([$observer, 'afterGetResult'])) {
                    $observer->afterGetResult($this, $out);
                }
            }

            return $out;
        }

        $result = $parser($out);

        if (is_array($result) && isset($result['result'])) {

            $convert = $this->convert;

            if (is_callable($convert)) {
                $return = static::doConvert($result['result'], $convert);
            } else {
                $return = $result['result'];
            }

            //
            // After getResult #2
            //
            foreach ($observers as $observer) {
                if (is_callable([$observer, 'afterGetResult'])) {
                    $observer->afterGetResult($this, $return);
                }
            }

            return $return;

        } else {
            throw new Exception('Shell getResult command failed, closure must return array contain "result" key');
        }
    }

    public function getPaginate($page = 1, $perpage = 10, $total = false)
    {
        $ret = [];

        $wrapper = static::getWrapper();
        $observers = $this->getObserverInstances();

        //
        // Before getPaginate
        //
        foreach ($observers as $observer) {
            if (is_callable([$observer, 'beforeGetPaginate'])) {
                $observer->beforeGetPaginate($this);
            }
        }

        //
        // Modify template command
        // append with `wc -l` to find total result row
        //
        if (!is_numeric($total)) {
            $template = $this->template;
            $template[0] = sprintf('%s | wc -l ', $template[0]);

            try {
                $out = $wrapper->exec($template, $this->args);
            } catch (ShellErrorException $e) {

                $bt = debug_backtrace();
                $caller = array_shift($bt);

                foreach ($observers as $observer) {
                    if (is_callable([$observer, 'onErrorGetPaginate'])) {
                        $observer->onErrorGetPaginate($this, $caller);
                    }
                }
                throw $e;
            }

            $out = trim($out);
            if (is_numeric($out)) {
                $total = $out;
            }
        }

        //
        // Calculates paginate values
        //
        $paginate = $wrapper->paginate($total, $page, $perpage);
        extract($paginate);

        $tail = $page * $perpage;
        $head = $limit;

        //
        // Append command with head and tail
        //
        $appendCommand = sprintf(' | tail -n %d | head -n %d ', $tail, $head);
        $template = $this->template;
        $template[0] = sprintf('%s %s', $template[0], $appendCommand);

        try {
            $out = $wrapper->exec($template, $this->args);
        } catch (ShellErrorException $e) {

            $bt = debug_backtrace();
            $caller = array_shift($bt);

            foreach ($observers as $observer) {
                if (is_callable([$observer, 'onErrorGetPaginate'])) {
                    $observer->onErrorGetPaginate($this, $caller);
                }
            }
            throw $e;
        }

        $parser = $this->parser;

        if (is_callable($parser)) {
            $result = $parser($out);
        } else {
            $result = $out;
        }

        if (is_array($result) && isset($result['result'])) {

            $convert = $this->convert;

            if (is_callable($convert)) {
                $result['result'] = static::doConvert($result['result'], $convert);
            }

            $ret['items'] = $result['result'];
            $ret['paging'] = $paginate;

            //
            // After getResult #1
            //
            foreach ($observers as $observer) {
                if (is_callable([$observer, 'afterGetPaginate'])) {
                    $observer->afterGetPaginate($this, $ret);
                }
            }

            return $ret;

        } else {
            throw new Exception('Shell getPaginate command failed');
        }
    }

    protected function doConvert(array $all, $convert)
    {
        if (is_callable($convert)) {
            array_walk($all, $convert);
        }

        return $all;
    }
}
