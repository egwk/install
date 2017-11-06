<?php

namespace Egwk\Install\Writings\Filter\Wrapper\Chain;

use Egwk\Install\Writings\Filter;
use Egwk\Install\Writings\Filter\Wrapper\Chain;

/**
 * Description of WritingsFilter
 *
 * @author Peter
 */
class Sentence extends Chain
    {

    public function __construct(Filter\Sentence $filter)
        {
        $this->filter = $filter;
        }

    }
