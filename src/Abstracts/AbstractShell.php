<?php

namespace Xemoe\Abstracts;

use \Xemoe\Contracts\ShellContract;
use \Exception;

abstract class AbstractShell implements ShellContract 
{
    protected $template;
    protected $args;
    protected $parser;
    protected $convert;

    public function __construct(array $template, array $args, $parser = false, $convert = false)
    {
        $this->template = $template;
        $this->args = $args;
        $this->parser = $parser;
        $this->convert = $convert;
    }

    abstract protected function getWrapper();

    public function toString()
    {
        return static::getWrapper()->variables_template($this->template, $this->args);
    }

    public function exec()
    {
        static::getWrapper()->exec($this->template, $this->args);
    }

    public function getResult()
    {
        $out = static::getWrapper()->exec($this->template, $this->args);

        $parser = $this->parser;

        //
        // Return if empty parser
        //
        if (!is_callable($parser)) {
            return $out;
        }

        $result = $parser($out);

        if (is_array($result) && isset($result['result'])) {

            $convert = $this->convert;

            if (is_callable($convert)) {
                $result['result'] = static::doConvert($result['result'], $convert);
            }

            return $result['result'];

        } else {
            throw new Exception('Shell getResult command failed, closure must return array contain "result" key');
        }
    }

    public function getPaginate($page = 1, $perpage = 10, $total = false)
    {
        $ret = [];

        $wrapper = static::getWrapper();

        //
        // Modify template command
        // append with `wc -l` to find total result row
        //
        if (!is_numeric($total)) {
            $template = $this->template;
            $template[0] = sprintf('%s | wc -l ', $template[0]);
            $out = $wrapper->exec($template, $this->args);
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
        $out = $wrapper->exec($template, $this->args);

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
