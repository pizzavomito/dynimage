<?php

namespace DynImage\Filter\Gamma;

use DynImage\FilterInterface;
use DynImage\Events;
use DynImage\Filter;

/**
 * Gamma correction
 *
 * @author pascal.roux
 */
class Gamma extends Filter implements FilterInterface {

    private $event = Events::AFTER_CREATE_IMAGE;

    public function __construct($arguments = null) {
        $default_arguments = array(
            'correction' => 1.3
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

            $this->imageManager->image->effects()->gamma($this->arguments['correction']);
        }
    }


}
