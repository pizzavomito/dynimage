<?php

namespace DynImage;

class Fire {
    
    private $addon;
    
    public function __construct($addon=null) {
     
        $this->addon = array();
    }
    
    public function connect($request,$app) {
        
        foreach ($this->addon as $addon) {
            $app['monolog']->addDebug(get_class($addon) .' connecting');
            $addon->connect($request,$app);
        }
    }
    
    public function add($addon) {
        array_push($this->addon,$addon);
    }

}

