<?php

namespace Xemoe\Abstracts;

use \Xemoe\Contracts\WrapperContract;
use \Exception;

abstract class AbstractWrapper implements WrapperContract
{
    public function paging($paginator)
    {
        $from = ($paginator->currentPage() - 1) * $paginator->perPage() + 1;
        $to = $from + $paginator->count() - 1;
        $total = $paginator->total();

        return  [
            'from' => $from,
            'to' => $to,
            'total' => $total,
        ];
    }

    public function paginate($total, $page, $perpage)
    {
        if (!is_numeric($total)) {
            throw new Exception("Paginate required total row argument");
        }

        if (!is_numeric($page) or $page < 1) {
            $page = 1;
        }

        if (!is_numeric($perpage) or $perpage < 1) {
            $perpage = 10;
        }

        $pages = sprintf('%d', $total / $perpage) + ($total % $perpage == 0 ? 0 : 1);
        $from = 1 + (($page - 1)* $perpage);
        $to = (($page - 1) * $perpage) + ($page === $pages ? $total % $perpage : $perpage);
        $limit = $page == $pages ? ($total % $perpage == 0 ? $perpage : $total % $perpage) : $perpage;

        return compact(['total', 'from', 'to', 'pages', 'page', 'perpage', 'limit']);
    }

    public function exec(array $template, array $vars = [])
    {
        $cmd = static::variables_template($template, $vars);
        $output = shell_exec($cmd);

        return $output;
    }

    public function variables_template(array $template, array $vars = [])
    {
        $wrap = false;

        if (sizeof($template) == 1) {
            $wrap = $template[0];
        } elseif (sizeof($template) == 2) {

            $arguments = explode(',', $template[1]);
            $args = new \stdClass();
            foreach ($arguments as $name) {
                $args->$name = $vars[$name];
            }

            $wrap = vsprintf($template[0], $args); 
        }

        return $wrap;
    }

    public function numfmt($size, $unit = 'B')
	{
	    if ($size <= 0) {
	        return "0 K" . $unit;
	    }

	    $base = log($size) / log(1024);
	    $floor = floor($base);
	    $suffix = ["", "K", "M", "G", "T"][$floor];

	    return sprintf('%.0f %s%s', pow(1024,$base - $floor), $suffix, $unit);
	}
}
