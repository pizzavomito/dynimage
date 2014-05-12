<?php

namespace DynImage\Filter;

use DynImage\FilterInterface;
use Imagine\Image\Box;
use Imagine\Image\Point;
use Imagine\Image\Fill\Gradient\Vertical;
use DynImage\Events;
use DynImage\Filter;

class Reflect extends Filter implements FilterInterface {

    protected $event = Events::FINISH_CREATE_IMAGE;
    protected $default_arguments = array(
        'color' => 'ffffff'
    );

    public function apply() {
        
        $size = $this->dynimage->image->getSize();
        $canvas = new Box($size->getWidth(), $size->getHeight() * 2);

        $palette = new \Imagine\Image\Palette\RGB();
        $white = $palette->color($this->arguments['color']);

        $fill = new Vertical(
                $size->getHeight(), $white->darken(127), $white
        );

        $tr = $this->dynimage->imagine->create($size)
                ->fill($fill);

        $reflection = $this->dynimage->image->copy()
                ->flipVertically()
                ->applyMask($tr)
        ;
        $palette = new \Imagine\Image\Palette\RGB();
        $color = $palette->color($this->arguments['color']);

        $this->dynimage->image = $this->dynimage->imagine->create($canvas, $color)
                ->paste($this->dynimage->image, new Point(0, 0))
                ->paste($reflection, new Point(0, $size->getHeight()));
    }

}
