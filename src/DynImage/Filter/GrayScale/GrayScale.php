<?php

namespace DynImage\Filter\GrayScale;

use DynImage\FilterInterface;
use DynImage\Filter;
use DynImage\Events;

/**
 * Noir et blanc
 *
 * @author pascal.roux
 */
class GrayScale extends Filter implements FilterInterface {

    
    protected $event = Events::EARLY_APPLY_FILTER;
        
    public function apply() {


        $this->imageManager->image->effects()->grayscale();
    }

   
}
