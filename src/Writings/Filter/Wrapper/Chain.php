<?php

namespace Egwk\Install\Writings\Filter\Wrapper;

use Egwk\Install\Writings\Filter;

/**
 * Description of WritingsFilter
 *
 * @author Peter
 */
class Chain
    {

    protected $data = null;
    protected $filter;

    public function __construct(Filter $filter)
        {
        $this->filter = $filter;
        }

    public function getFilter(): Filter
        {
        return $this->filter;
        }

    public function get()
        {
        return $this->data;
        }

    public function set($data)
        {
        $this->data = $data;
        return $this;
        }

    public function __call($methodName, $args)
        {
        if (is_callable([$this->filter, $methodName]))
            {
            $this->data = $this->filter->{$methodName}($this->data);
            }
        return $this;
        }

    }
