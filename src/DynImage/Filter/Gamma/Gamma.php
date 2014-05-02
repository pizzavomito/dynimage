<?php

namespace DynImage\Filter\Gamma;

use DynImage\FilterInterface;
use DynImage\Events;
use DynImage\Filter;

/**
 * Gamma correction
 *
 * @author pascal.roux
 */
class Gamma extends Filter implements FilterInterface {

    protected $prefix_parameter = 'gamma.';
    protected $event = Events::LATE_APPLY_FILTER;
    protected $default_arguments = array(
        'correction' => 1.3
    );

    public function apply() {


        $this->imageManager->image->effects()->gamma($this->arguments['correction']);
    }

}
