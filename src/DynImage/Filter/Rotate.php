<?php

namespace DynImage\Filter;

use DynImage\FilterInterface;
use DynImage\Filter;
use DynImage\Events;

class Rotate extends Filter implements FilterInterface
{
    protected $angle = 45;
    protected $event = Events::LATE_APPLY_FILTER;

    public function apply()
    {
        $this->dynImageAware->getImage()->rotate($this->angle);
    }

    /**
     * @return int
     */
    public function getAngle()
    {
        return $this->angle;
    }

    /**
     * @param int $angle
     * @return Rotate
     */
    public function setAngle($angle)
    {
        $this->angle = $angle;
        return $this;
    }
}
