<?php

namespace DynImage\Filter\Border;

use DynImage\FilterInterface;
use DynImage\Events;
use DynImage\Filter;

/**
 * Ajoute une bordure
 *
 * @author pascal.roux
 */
class Border extends Filter implements FilterInterface {

    protected $prefix_parameter = 'border.';
    protected $event = Events::LATE_APPLY_FILTER;
    protected $default_arguments = array(
            'color' => '000000',
            'height' => 5,
            'width' => 5,
        );
    
    

    public function apply() {

        $color = $this->imageManager->image->palette()->color($this->arguments['color']);
        $c = new \Imagine\Filter\Advanced\Border($color, $this->arguments['width'], $this->arguments['height']);

        $this->imageManager->image = $c->apply($this->imageManager->image);
    }

}
