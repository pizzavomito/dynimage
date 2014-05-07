<?php

namespace DynImage\Filter\Blur;

use DynImage\FilterInterface;
use DynImage\Events;
use DynImage\Filter;

/**
 * Blur 
 *
 * @author pascal.roux
 */
class Blur extends Filter implements FilterInterface {

    protected $prefix_parameter = 'blur.';
    protected $event = Events::AFTER_CREATE_IMAGE;
     protected $default_arguments = array(
        'sigma' => 3
    );

 
    public function apply() {

         $this->imageManager->image->effects()->blur($this->arguments['sigma']);
 
    }

}
