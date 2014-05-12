<?php

namespace DynImage\Filter;

use DynImage\FilterInterface;
use Imagine\Image\Box;
use DynImage\Filter;
use DynImage\Events;

class Thumb extends Filter implements FilterInterface {

    protected $event = Events::AFTER_CREATE_IMAGE;
    protected $default_arguments = array(
        'width' => 100,
        'height' => 100
    );

    public function apply() {
         
        $this->dynimage->image = $this->dynimage->image->thumbnail(new Box($this->arguments['width'], $this->arguments['height']));
        
       
    }

}
