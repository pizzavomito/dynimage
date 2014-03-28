<?php

namespace DynImage;

class Filter {
    protected $arguments;
    
    protected $imagerequest;

    public function __construct($arguments=null) {
        $this->arguments = $arguments;
        error_log('entering filter construct');
    }
    
    public function connect($imagerequest,$dispatcher) {

        $this->imagerequest = $imagerequest;
        error_log('entering filter connect');
        $dispatcher->addListener($this->getEvent(), array($this, 'listener'));
    }
    
    
     

}

