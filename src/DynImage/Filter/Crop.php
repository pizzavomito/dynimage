<?php

namespace DynImage\Filter;

use DynImage\FilterInterface;
use DynImage\Events;
use DynImage\Filter;
use Imagine\Image\Box;
use Imagine\Image\Point;

class Crop extends Filter implements FilterInterface
{
    protected $x = 0;
    protected $y = 0;
    protected $height = 100;
    protected $width = 100;

    protected $event = Events::LATE_APPLY_FILTER;

    public function apply()
    {
        $this->dynImageAware->getImage()->crop(new Point($this->x, $this->y), new Box($this->width, $this->height));
    }

    /**
     * @return int
     */
    public function getX()
    {
        return $this->x;
    }

    /**
     * @param int $x
     * @return Crop
     */
    public function setX($x)
    {
        $this->x = $x;
        return $this;
    }

    /**
     * @return int
     */
    public function getY()
    {
        return $this->y;
    }

    /**
     * @param int $y
     * @return Crop
     */
    public function setY($y)
    {
        $this->y = $y;
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
     * @return Crop
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
     * @return Crop
     */
    public function setWidth($width)
    {
        $this->width = $width;
        return $this;
    }
}
