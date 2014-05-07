<?php

namespace DynImage\Filter;

use DynImage\FilterInterface;
use DynImage\Filter;
use DynImage\Events;

class Rotate extends Filter implements FilterInterface {

    protected $event = Events::LATE_APPLY_FILTER;
    protected $default_arguments = array(
        'angle' => 45
    );

    public function apply() {


        $this->imageManager->image->rotate($this->arguments['angle']);
    }

}
