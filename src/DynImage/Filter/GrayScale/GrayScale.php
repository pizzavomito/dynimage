<?php

namespace DynImage\Filter\GrayScale;

use DynImage\FilterInterface;
use DynImage\Filter;
use DynImage\Events;


class GrayScale extends Filter implements FilterInterface {

    
    protected $event = Events::EARLY_APPLY_FILTER;
    protected $default_arguments = array();
        
    public function apply() {


        $this->imageManager->image->effects()->grayscale();
    }

   
}
