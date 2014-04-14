<?php

namespace DynImage\Filter\Reflect;

use Silex\Application;
use DynImage\FilterInterface;
use Symfony\Component\HttpFoundation\Request;
use Imagine\Image\Box;
use Imagine\Image\Color;
use Imagine\Image\Point;
use Imagine\Image\Fill\Gradient\Vertical;
use DynImage\Events;
use DynImage\Filter;

/**
 * Effet Reflection
 *
 * @author pascal.roux
 */
class Reflect extends Filter implements FilterInterface {

    protected $event = Events::FINISH_CREATE_IMAGE;

    public function __construct($arguments = null) {

        $default_arguments = array(
            'color' => 'ffffff'
        );
        if (is_null($arguments)) {
            $arguments = array();
        }
        $this->arguments = array_replace_recursive($default_arguments, $arguments);
    }

    public function getEvent() {
        return $this->event;
    }

    public function apply() {
        $size = $this->imageManager->image->getSize();
        $canvas = new Box($size->getWidth(), $size->getHeight() * 2);

        $palette = new \Imagine\Image\Palette\RGB();
        $white = $palette->color($this->arguments['color']);

        $fill = new Vertical(
                $size->getHeight(), $white->darken(127), $white
        );

        $tr = $this->imageManager->imagine->create($size)
                ->fill($fill);

        $reflection = $this->imageManager->image->copy()
                ->flipVertically()
                ->applyMask($tr)
        ;
        $palette = new \Imagine\Image\Palette\RGB();
        $color = $palette->color($this->arguments['color']);

        $this->imageManager->image = $this->imageManager->imagine->create($canvas, $color)
                ->paste($this->imageManager->image, new Point(0, 0))
                ->paste($reflection, new Point(0, $size->getHeight()));
    }

   
}
