<?php

namespace DynImage\Filter\Border;

use DynImage\FilterInterface;
use DynImage\Events;
use DynImage\Filter;

/**
 * Ajoute une bordure
 *
 * @author pascal.roux
 */
class Border extends Filter implements FilterInterface {

    protected $event = Events::LUNCH_APPLY_FILTER;

    public function __construct($arguments = null) {
        $default_arguments = array(
            'color' => '000000',
            'height' => 5,
            'width' => 5,
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
        if (!is_null($this->arguments)) {

            
            $color = $this->imageManager->image->palette()->color($this->arguments['color']);
            $c = new \Imagine\Filter\Advanced\Border($color, $this->arguments['width'], $this->arguments['height']);

            $this->imageManager->image = $c->apply($this->imageManager->image);
        }
    }

}
