<?php

namespace DynImage;

use DynImage\FilterInterface;

class Transformer {
    
    private $filters;
    
    public function __construct() {
     
        $this->filters = array();
    }
    
    public function add(FilterInterface $filter, $event=null) {
       
        if (!is_null($event)) {
            $filter->setEvent($event);
        }
        
        $this->filters[] = $filter;
    }

    public function getFilters() {

        return $this->filters;
    }
}

