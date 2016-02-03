<?php

namespace DynImage\Filter;

use DynImage\FilterInterface;
use DynImage\Events;
use DynImage\Filter;

class Colorize extends Filter implements FilterInterface
{
    protected $color = 'ffffff';

    protected $event = Events::EARLY_APPLY_FILTER;

    public function apply()
    {
        $color = $this->dynImageAware->getImage()->palette()->color($this->color);

        $this->dynImageAware->getImage()->effects()->colorize($color);
    }

    /**
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param string $color
     * @return Colorize
     */
    public function setColor($color)
    {
        $this->color = $color;
        return $this;
    }
}
