<?php

namespace DynImage;

class Filter {
    protected $arguments;
    
    protected $imageManager;
    
    protected $parameters;

    public function __construct($arguments=null) {
        $this->arguments = $arguments;
        
    }
    
    public function setEvent($event) {
        if (is_object($event)) {
            $event = $event->event;
        }
        $this->event = $event;
    }
    
    public function connect($imageManager,$dispatcher,$parameters) {

        $this->parameters = $parameters;
        
        $this->imageManager = $imageManager;
        
        $dispatcher->addListener($this->getEvent(), array($this, 'apply'));
    }
   

}

