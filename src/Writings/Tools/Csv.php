<?php

namespace Egwk\Install\Writings\Tools;

/**
 * Description of Csv
 *
 * @author Peter
 */
trait Csv
    {

    private function createCsv($glue = ',')
        {
        $data = [];
        for ($i = 1; $i < func_num_args(); $i++)
            {
            $arg = func_get_arg($i);
            if (is_object($arg))
                {
                $arg = (array) ($arg);
                }
            elseif (!is_array($arg))
                {
                $arg = [$arg];
                }
            $data = array_filter(array_merge($data, $arg), function($e)
                {
                return !is_array($e);
                });
            }
        return implode($glue, $data) . "\n";
        }

    }
