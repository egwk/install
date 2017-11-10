<?php

namespace Egwk\Install\Writings\Filter\Wrapper\Chain;

use Egwk\Install\Writings\Filter;
use Egwk\Install\Writings\Filter\Wrapper\Chain;

/**
 * Sentence Chain Filter wrapper class
 *
 * @author Peter
 */
class Sentence extends Chain
{

    /**
     * Class constructor
     *
     * @access public
     * @param Filter\Sentence $filter Sentence Filter object
     * @return void
     */
    public function __construct(Filter\Sentence $filter)
    {
        $this->filter = $filter;
    }

}
