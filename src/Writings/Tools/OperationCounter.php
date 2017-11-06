<?php

namespace Egwk\Install\Writings\Tools;

/**
 * Description of OperationCounter
 *
 * @author Peter
 */
trait OperationCounter
    {

    private $operationCounter;
    private $operationLimit;
    private $operationTermSignal;

    private function initCounterNoLimit()
        {
        $this->initCounter(0);
        }

    private function initCounter(int $limit)
        {
        $this->resetCounter();
        $this->setCounterLimit($limit);
        }

    private function resetCounter()
        {
        $this->operationCounter    = 0;
        $this->operationTermSignal = false;
        }

    private function setCounterLimit(int $limit)
        {
        $this->operationLimit = $limit;
        }

    private function counterTermSignal()
        {
        return $this->operationTermSignal;
        }

    private function stepCounter()
        {
        $this->operationCounter++;
        if (0 !== $this->operationLimit && $this->operationCounter >= $this->operationLimit)
            {
            $this->operationTermSignal = true;
            }
        }

    }
