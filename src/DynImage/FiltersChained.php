<?php

namespace DynImage;

class FiltersChained {
    
    private $filters;
    
    public function __construct() {
     
        $this->filters = array();
    }
    
    public function add($filter) {
       
        array_push($this->filters,$filter);
    }

}

