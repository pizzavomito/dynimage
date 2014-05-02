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

    public function __construct($arguments = null) {
        $default_arguments = array(
            'sigma' => 3
        );
        if (is_null($arguments)) {
            $arguments = array();
        }
        $this->arguments = array_replace_recursive($default_arguments, $arguments);
    }


    public function apply() {

         $this->imageManager->image->effects()->blur($this->arguments['sigma']);
 
    }

}
