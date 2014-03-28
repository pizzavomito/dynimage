<?php

namespace DynImage\Filter\GrayScale;

use DynImage\FilterInterface;
use DynImage\Filter;
use DynImage\Events;

/**
 * Noir et blanc
 *
 * @author pascal.roux
 */
class GrayScale extends Filter implements FilterInterface {

    private $event = Events::AFTER_CREATE_IMAGE;
    
    public function getEvent() {
        return $this->event;
    }
    
    public function apply() {


        $this->imageManager->image->effects()->grayscale();
    }

   
}
