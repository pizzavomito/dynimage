<?php

namespace DynImage;

class AddonBag {
    
    private $addon;
    
    public function __construct($addon=null) {
     
        $this->addon = array();
    }
    
    public function connect($module,$dispatcher) {
        
        foreach ($this->addon as $addon) {
            //$app['monolog']->addDebug(get_class($addon) .' connecting');
            //$addon->connect($module,$dispatcher);
        }
    }
    
    public function add($addon) {
        array_push($this->addon,$addon);
    }

}

