<?php

namespace DynImage\Filter\Experimental;

use DynImage\FilterInterface;
use DynImage\Events;
use DynImage\Filter;

class DropShadow extends Filter implements FilterInterface {

    protected $event = Events::LATE_APPLY_FILTER;
    protected $default_arguments = array(
        'opacity' => 80,
        'x' => 5,
        'y' => 5,
        'sigma' => 3,
        'color' => '#000000'
    );

    public function apply() {
       
        if ($this->dynimage->getDriver() == 'Imagick') {
            
            $palette = new \Imagine\Image\Palette\RGB();
            $color = $palette->color($this->arguments['color']);

            $shadow = $this->dynimage->imagine->create($this->dynimage->image->getSize(), $color);

            $im = new \Imagick();
            $im->readImageBlob($shadow);
            $im->shadowimage($this->arguments['opacity'], $this->arguments['sigma'], $this->arguments['x'], $this->arguments['y']);
            $image = new \Imagick();
            $image->readImageBlob($this->dynimage->image);

            $im->compositeImage($image, \Imagick::COMPOSITE_OVER, 0, 0);

            $this->dynimage->image = new \Imagine\Imagick\Image($im, $this->dynimage->image->palette(), $this->dynimage->imagine->getMetadataReader()->readData($im));
            
            
        }
    }

}
