<?php

namespace DynImage\Filter;

use DynImage\FilterInterface;
use DynImage\Events;
use DynImage\Filter;

class Border extends Filter implements FilterInterface
{
    protected $color = '000000';

    protected $height = 5;

    protected $width = 5;

    protected $event = Events::LATE_APPLY_FILTER;


    public function apply()
    {
        $color = $this->dynImageAware->getImage()->palette()->color($this->color);
        $border = new \Imagine\Filter\Advanced\Border($color, $this->width, $this->height);
        $this->dynImageAware->setImage($border->apply($this->dynImageAware->getImage()));
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
     * @return Border
     */
    public function setColor($color)
    {
        $this->color = $color;
        return $this;
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param int $height
     * @return Border
     */
    public function setHeight($height)
    {
        $this->height = $height;
        return $this;
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param int $width
     * @return Border
     */
    public function setWidth($width)
    {
        $this->width = $width;
        return $this;
    }

    /**
     * @return string
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @param string $event
     * @return Border
     */
    public function setEvent($event)
    {
        $this->event = $event;
        return $this;
    }
}
