<?php

namespace DynImage\Filter\Colorize;

use DynImage\FilterInterface;
use DynImage\Events;
use DynImage\Filter;
/**
 * Colorize 
 *
 * @author pascal.roux
 */
class Colorize extends Filter implements FilterInterface {

    private $event = Events::AFTER_CREATE_IMAGE;

    public function __construct($arguments) {
        $this->arguments = $arguments;
    }

    public function getEvent() {
        return $this->event;
    }
    
    public function apply() {

        if (!is_null($this->arguments)) {
            error_log('color:'.$this->arguments['color']);
            $color = $this->imageManager->image->palette()->color($this->arguments['color']);

            $this->imageManager->image->effects()->colorize($color);
        }
    }

}