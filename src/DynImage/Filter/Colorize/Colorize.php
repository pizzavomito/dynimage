<?php

namespace DynImage\Filter\Colorize;

use DynImage\FilterInterface;
use DynImage\Events;
use DynImage\Filter;

/**
 * Colorize 
 *
 * @author pascal.roux
 */
class Colorize extends Filter implements FilterInterface {

    protected $prefix_parameter = 'colorize.';
    protected $event = Events::EARLY_APPLY_FILTER;
    protected $default_arguments = array(
        'color' => '#ffffff'
    );

    public function apply() {


        $color = $this->imageManager->image->palette()->color($this->arguments['color']);

        $this->imageManager->image->effects()->colorize($color);
    }

}
