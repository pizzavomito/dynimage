<?php

namespace DynImage\Filter;

use DynImage\FilterInterface;
use DynImage\Events;
use DynImage\Filter;

class Gamma extends Filter implements FilterInterface {

    protected $event = Events::LATE_APPLY_FILTER;
    protected $default_arguments = array(
        'correction' => 1.3
    );

    public function apply() {


        $this->dynimage->image->effects()->gamma($this->arguments['correction']);
    }

}
