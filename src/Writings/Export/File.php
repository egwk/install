<?php

namespace Egwk\Install\Writings\Export;

use Egwk\Install\Writings\Filter;

/**
 * Description of File
 *
 * @author Peter
 */
abstract class File extends Export
    {

    protected $outputFile = "";

    public function __construct(Filter $filter, $outputFile = "./data")
        {
        parent::__construct($filter);
        $this->initOutputFile($outputFile);
        }

    protected abstract function initOutputFile($outputFile = "./data");

    protected function addFileNameModifier($outputFile, $modifier)
        {
        $pathParts = pathinfo($outputFile);

        return $pathParts['dirname']
                . DIRECTORY_SEPARATOR
                . $pathParts['filename']
                . ".$modifier."
                . $pathParts['extension'];
        }

    protected function resetOutputFile($modifier = "")
        {
        $this->writeOutputFileBase('', $modifier);
        }

    protected function writeOutputFile($data, $modifier = "")
        {
        $this->writeOutputFileBase($data, $modifier, FILE_APPEND);
        }

    protected function writeOutputFileBase($data, $modifier = "", int $flags = 0)
        {
        file_put_contents($this->addFileNameModifier($this->outputFile, $modifier), $data, $flags);
        }

    }
