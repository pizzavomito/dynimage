<?php

namespace DynImage\Filter;

use DynImage\FilterInterface;
use DynImage\Filter;
use DynImage\Events;

class GrayScale extends Filter implements FilterInterface {

    protected $event = Events::EARLY_APPLY_FILTER;

    public function __construct($arguments = null) {
        
    }

    public function apply() {


        $this->imageManager->image->effects()->grayscale();
    }

}