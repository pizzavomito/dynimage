<?php

namespace DynImage\Filter\Crop;

use DynImage\FilterInterface;
use DynImage\Events;
use DynImage\Filter;
use Imagine\Image\Box;
use Imagine\Image\Point;

/**
 * Description of Crop
 *
 * @author pascal.roux
 */
class Crop extends Filter implements FilterInterface {

    protected $event = Events::LATE_APPLY_FILTER;
    
    protected $default_arguments = array(
            'x' => 0,
            'y' => 0,
            'height' => 100,
            'width' => 100
        );

    protected $prefix_parameter ='crop.';
    

    public function apply() {


            $this->imageManager->image->crop(new Point($this->arguments['x'], $this->arguments['y']), new Box($this->arguments['width'], $this->arguments['height']));
       
    }

}

