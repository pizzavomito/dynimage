<?php



namespace DynImage;


interface FilterInterface
{
    
    
    public function apply();
    public function getArguments();
    public function getPrefixParameter();
    public function setEvent($event);
    public function connect($imageManager, $dispatcher, $parameters);
    
}

