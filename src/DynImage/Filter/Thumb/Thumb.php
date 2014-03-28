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

    
  
    private $event = Events::AFTER_CREATE_IMAGE;
    
    public function getEvent() {
        return $this->event;
    }
    
    public function listener() {
        if (!is_null($this->arguments)) {

             $this->imagerequest->image = $this->imagerequest->image->thumbnail(new Box($this->arguments['width'], $this->arguments['height']));
        }
    }
    
}