<?php

namespace DynImage\Filter\Experimental;

use DynImage\FilterInterface;
use DynImage\Events;
use DynImage\Filter;

class RoundCorners extends Filter implements FilterInterface {

    protected $event = Events::LATE_APPLY_FILTER;
    protected $default_arguments = array(
        'x' => 5,
        'y' => 3
    );

    public function apply() {

        if ($this->dynimage->options['driver'] == 'Imagick') {
            $this->dynimage->image->getImagick()->roundCorners($this->arguments['x'], $this->arguments['y']);
        }
    }

}
