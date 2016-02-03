<?php

namespace DynImage\Filter;

use DynImage\FilterInterface;
use DynImage\Events;
use DynImage\Filter;
use Imagine\Image\Box;

class Resize extends Filter implements FilterInterface
{
    protected $height = 100;

    protected $width = 100;

    protected $event = Events::LATE_APPLY_FILTER;


    public function apply()
    {
        $this->dynImageAware->getImage()->resize(new Box($this->width, $this->height));
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
     * @return Resize
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
     * @return Resize
     */
    public function setWidth($width)
    {
        $this->width = $width;
        return $this;
    }
}
