<?php

namespace DynImage\Filter\RoundCorners;

use DynImage\FilterInterface;
use DynImage\Events;
use DynImage\Filter;

/**
 * Description of RoundCorners
 *
 * @author pascal.roux
 */
class RoundCorners extends Filter implements FilterInterface {

    protected $event = Events::LATE_APPLY_FILTER;
    
    protected $default_arguments = array(
            'x' => 5,
            'y' => 3
        );

    protected $prefix_parameter ='roundcorners.';
    

    public function apply() {

        if ($this->parameters['driver'] == 'Imagick') {
            $this->imageManager->image->getImagick()->roundCorners($this->arguments['x'], $this->arguments['y']);
        }
    }

}
