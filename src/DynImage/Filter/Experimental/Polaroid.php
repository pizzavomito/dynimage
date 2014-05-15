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

        if ($this->dynimage->getDriver() == 'Imagick') {

            $im = new \Imagick();

            $im->readImageBlob($this->dynimage->image);
            $angle = $this->arguments['angle'];
            if ($this->arguments['random_angle']) {
                $angle = rand(-45, 45);
            }
            $im->polaroidImage(new \ImagickDraw(), $angle);

            $this->dynimage->image = new \Imagine\Imagick\Image($im, $this->dynimage->image->palette(), $this->dynimage->imagine->getMetadataReader()->readData($im));
        }
    }

}
