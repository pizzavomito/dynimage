<?php

namespace DynImage;

use Symfony\Component\EventDispatcher\EventDispatcher;
use DynImage\DynImage;

class Filter {

    protected $arguments;
    protected $dynimage;

    public function __construct($arguments = null) {
        if (is_null($arguments)) {
            $arguments = array();
        }
        $this->arguments = array_replace($this->default_arguments, $arguments);
    }

    public function setEvent($event) {

        $this->event = $event;
    }

    public function connect(DynImage $dynimage, EventDispatcher $dispatcher) {
     
        $this->dynimage = $dynimage;

        $dispatcher->addListener($this->event, array($this, 'apply'));
    }

    public function getArguments() {
        return $this->arguments;
    }


}
