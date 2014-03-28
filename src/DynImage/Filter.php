<?php

namespace DynImage;

class Filter {
    protected $arguments;
    
    protected $imageManager;

    public function __construct($arguments=null) {
        $this->arguments = $arguments;
        
    }
    
    public function connect($imageManager,$dispatcher) {

        $this->imageManager = $imageManager;
        
        $dispatcher->addListener($this->getEvent(), array($this, 'apply'));
    }
    
    
     

}

