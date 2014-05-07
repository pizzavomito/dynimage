<?php

namespace DynImage\Filter\Experimental;

use DynImage\FilterInterface;
use DynImage\Events;
use DynImage\Filter;

class Polaroid extends Filter implements FilterInterface {

    protected $event = Events::FINISH_CREATE_IMAGE;
    protected $default_arguments = array(
        'angle' => 0,
        'random_angle' => false
    );

    public function apply() {

        if ($this->options['driver'] == 'Imagick') {

            $im = new \Imagick();

            $im->readImageBlob($this->imageManager->image);
            $angle = $this->arguments['angle'];
            if ($this->arguments['random_angle']) {
                $angle = rand(-45, 45);
            }
            $im->polaroidImage(new \ImagickDraw(), $angle);

            $this->imageManager->image = new \Imagine\Imagick\Image($im, $this->imageManager->image->palette(), $this->imageManager->imagine->getMetadataReader()->readData($im));
        }
    }

}
