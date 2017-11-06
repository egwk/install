<?php

namespace Egwk\Install\Writings;

use Illuminate\Support\Facades\Redis;
use Egwk\Install\Writings\Filter;

class Downloader
    {

    protected $process = null;

    protected function getIterator()
        {
        $api      = new API();
        $request  = new API\Request($api);
        $iterator = new API\Iterator($request);
        return $iterator;
        }

    protected function getExport()
        {
        $dictPath  = base_path('vendor/phpmorphy/en');
        $morphy    = new Morphy($dictPath);
        $filter    = new Filter($morphy);
        $export    = new Export\CsvDump($filter);
        return $export;
        }

    public function install()
        {
        $this->process = new Process($this->getIterator(), $this->getExport());
        $this->process->writings();
        }

    }
