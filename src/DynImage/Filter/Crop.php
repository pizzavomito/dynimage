<?php

namespace DynImage\Filter;

use DynImage\FilterInterface;
use DynImage\Events;
use DynImage\Filter;
use Imagine\Image\Box;
use Imagine\Image\Point;

class Crop extends Filter implements FilterInterface {

    protected $event = Events::LATE_APPLY_FILTER;
    protected $default_arguments = array(
        'x' => 0,
        'y' => 0,
        'height' => 100,
        'width' => 100
    );

    public function apply() {


        $this->dynimage->image->crop(new Point($this->arguments['x'], $this->arguments['y']), new Box($this->arguments['width'], $this->arguments['height']));
    }

}
