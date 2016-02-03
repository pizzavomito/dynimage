<?php

namespace DynImage\Filter;

use DynImage\FilterInterface;
use Imagine\Image\Box;
use Imagine\Image\Point;
use Imagine\Image\Fill\Gradient\Vertical;
use DynImage\Events;
use DynImage\Filter;

class Reflect extends Filter implements FilterInterface
{
    protected $color = 'ffffff';
    
    protected $event = Events::FINISH_CREATE_IMAGE;

    public function apply()
    {
        $size = $this->dynImageAware->getImage()->getSize();
        $canvas = new Box($size->getWidth(), $size->getHeight() * 2);

        $palette = new \Imagine\Image\Palette\RGB();
        $white = $palette->color($this->color);

        $fill = new Vertical(
                $size->getHeight(), $white->darken(127), $white
        );

        $tr = $this->dynImageAware->getImagine()->create($size)
                ->fill($fill);

        $reflection = $this->dynImageAware->getImage()->copy()
                ->flipVertically()
                ->applyMask($tr)
        ;
        $palette = new \Imagine\Image\Palette\RGB();
        $color = $palette->color($this->color);

        $this->dynImageAware->setImage($this->dynImageAware->getImagine()->create($canvas, $color)
                ->paste($this->dynImageAware->getImage(), new Point(0, 0))
                ->paste($reflection, new Point(0, $size->getHeight())));
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
