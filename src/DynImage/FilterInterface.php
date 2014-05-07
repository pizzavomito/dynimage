<?php



namespace DynImage;


interface FilterInterface
{
    
    
    public function apply();
    public function getArguments();
    public function setEvent($event);
    public function connect($imageManager, $dispatcher, $options);
    
}

