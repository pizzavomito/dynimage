<?php

namespace DynImage;

class FilterManager {
    
    private $filters;
    
    public function __construct() {
     
        $this->filters = array();
    }
    
       
    public function add($filter) {
        array_push($this->filters,$filter);
    }

}

