<?php

namespace Egwk\Install\Writings\Export;

use Egwk\Install\Writings\Filter;

/**
 * Description of Export
 *
 * @author Peter
 */
abstract class Export
    {

    protected $filter              = null;
    protected $sentenceFilter      = null;
    protected $chainFilter         = null;
    protected $sentenceChainFilter = null;

    public abstract function export($paragraph);

    public function __construct(Filter $filter)
        {
        $this->filter              = $filter;
        $this->sentenceFilter      = new Filter\Sentence($this->filter);
        $this->chainFilter         = new Filter\Wrapper\Chain($this->filter);
        $this->sentenceChainFilter = new Filter\Wrapper\Chain\Sentence($this->sentenceFilter);
        }

    }
