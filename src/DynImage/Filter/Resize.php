<?php

namespace DynImage\Filter;

use DynImage\FilterInterface;
use DynImage\Events;
use DynImage\Filter;
use Imagine\Image\Box;

class Resize extends Filter implements FilterInterface {

    protected $event = Events::LATE_APPLY_FILTER;
    protected $default_arguments = array(
        'width' => 100,
        'height' => 100
    );

    public function apply() {

        $this->dynimage->image->resize(new Box($this->arguments['width'], $this->arguments['height']));
    }

}
