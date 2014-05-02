<?php

namespace DynImage\Filter\Resize;

use DynImage\FilterInterface;
use DynImage\Events;
use DynImage\Filter;
use Imagine\Image\Box;

/**
 * Resize
 *
 * @author pascal.roux
 */
class Resize extends Filter implements FilterInterface {

    protected $prefix_parameter = 'resize.';
    protected $event = Events::LATE_APPLY_FILTER;
    protected $default_arguments = array(
        'width' => 100,
        'height' => 100
    );

    public function apply() {
 

        $this->imageManager->image->resize(new Box($this->arguments['width'], $this->arguments['height']));
    }

}

