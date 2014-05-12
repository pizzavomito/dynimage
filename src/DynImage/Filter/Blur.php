<?php

namespace DynImage\Filter;

use DynImage\FilterInterface;
use DynImage\Events;
use DynImage\Filter;

class Blur extends Filter implements FilterInterface {

    protected $event = Events::AFTER_CREATE_IMAGE;
    protected $default_arguments = array(
        'sigma' => 3
    );

    public function apply() {

        $this->dynimage->image->effects()->blur($this->arguments['sigma']);
    }

}
