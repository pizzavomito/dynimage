<?php

namespace DynImage\Filter\Thumb;

use DynImage\FilterInterface;
use Imagine\Image\Box;
use DynImage\Filter;
use DynImage\Events;

/**
 * Creation de miniature
 *
 * @author pascal.roux
 */
class Thumb extends Filter implements FilterInterface {

    protected $prefix_parameter = 'thumb.';
    protected $event = Events::AFTER_CREATE_IMAGE;
    protected $default_arguments = array(
        'width' => 100,
        'height' => 100
    );

    public function apply() {
        $this->imageManager->image = $this->imageManager->image->thumbnail(new Box($this->arguments['width'], $this->arguments['height']));
    }

}
