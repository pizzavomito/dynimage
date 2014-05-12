<?php

namespace DynImage\Filter;

use DynImage\FilterInterface;
use DynImage\Events;
use DynImage\Filter;

class Colorize extends Filter implements FilterInterface {

    protected $event = Events::EARLY_APPLY_FILTER;
    protected $default_arguments = array(
        'color' => '#ffffff'
    );

    public function apply() {


        $color = $this->dynimage->image->palette()->color($this->arguments['color']);

        $this->dynimage->image->effects()->colorize($color);
    }

}
