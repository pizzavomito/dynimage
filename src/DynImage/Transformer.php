<?php

namespace DynImage;

class Transformer {
    
    private $filters;
    
    public function __construct() {
     
        $this->filters = array();
    }
    
    public function add($filter) {
       
        array_push($this->filters,$filter);
    }

    public function getFilters() {
        return $this->filters;
    }
}

