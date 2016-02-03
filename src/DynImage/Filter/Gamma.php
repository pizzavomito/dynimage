<?php

namespace DynImage\Filter;

use DynImage\FilterInterface;
use DynImage\Events;
use DynImage\Filter;

class Gamma extends Filter implements FilterInterface
{
    protected $correction = 1.3;

    protected $event = Events::LATE_APPLY_FILTER;

    public function apply()
    {
        $this->dynImageAware->getImage()->effects()->gamma($this->correction);
    }

    /**
     * @return float
     */
    public function getCorrection()
    {
        return $this->correction;
    }

    /**
     * @param float $correction
     */
    public function setCorrection($correction)
    {
        $this->correction = $correction;

        return $this;
    }
}
