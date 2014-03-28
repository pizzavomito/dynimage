<?php

namespace DynImage\Filter\Polaroid;

use DynImage\FilterInterface;
use DynImage\Events;
use DynImage\Filter;

/**
 * Effet Polaroid
 *
 * @author pascal.roux
 */
class Polaroid extends Filter implements FilterInterface {

    private $event = Events::AFTER_CREATE_IMAGE;

    public function __construct($arguments = null) {
        $default_arguments = array(
            'angle' => 25,
            'random_angle' => false
        );
        if (is_null($arguments)) {
            $arguments = array();
        }
        $this->arguments = array_replace_recursive($default_arguments, $arguments);
    }

    public function getEvent() {
        return $this->event;
    }

    public function listener() {

        if ($this->imagerequest->arguments['lib'] == 'Imagick') {

            if (!is_null($this->arguments)) {

                $im = new \Imagick();
                //$im = $this->imagerequest->image->getImagick();
                $im->readImageBlob($this->imagerequest->image);
                $angle = $this->arguments['angle'];
                if ($this->arguments['random_angle']) {
                    $angle = rand(-45, 45);
                }
                $im->polaroidImage(new \ImagickDraw(), $angle);
              
                $this->imagerequest->image = new \Imagine\Imagick\Image($im, $this->imagerequest->image->palette());
            }
        }
    }

}
