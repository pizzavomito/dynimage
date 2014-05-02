<?php

namespace DynImage\Filter\Rotate;

use DynImage\FilterInterface;
use DynImage\Filter;
use DynImage\Events;

/**
 * Rotate
 *
 * @author pascal.roux
 */
class Rotate extends Filter implements FilterInterface {

    
    protected $event = Events::LATE_APPLY_FILTER;
    protected $prefix_parameter ='rotate.';
    protected $default_arguments = array(
            'angle' => 45
        );
        
    public function apply() {


        $this->imageManager->image->rotate($this->arguments['angle']);
    }

   
}
