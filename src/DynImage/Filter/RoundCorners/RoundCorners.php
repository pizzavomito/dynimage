<?php

namespace DynImage\Filter\RoundCorners;

use DynImage\FilterInterface;
use DynImage\Events;
use DynImage\Filter;

/**
 * Description of RoundCorners
 *
 * @author pascal.roux
 */
class RoundCorners extends Filter implements FilterInterface {

    private $event = Events::AFTER_CREATE_IMAGE;

    public function __construct($arguments = null) {

        $default_arguments = array(
            'x' => 5,
            'y' => 3
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

        if ($this->imageManager->arguments['lib'] == 'Imagick') {
            $this->imageManager->image->getImagick()->roundCorners($this->arguments['x'], $this->arguments['y']);
        }
    }

}
