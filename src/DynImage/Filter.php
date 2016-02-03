<?php

namespace DynImage;

use Symfony\Component\EventDispatcher\EventDispatcher;
use DynImage\DynImageAware;

class Filter {

    /**
     * @var DynImageAware
     */
    protected $dynImageAware;


    public function setEvent($event) {

        $this->event = $event;
    }

    public function connect(DynImageAware $dynImageAware, EventDispatcher $dispatcher) {
     
        $this->dynImageAware = $dynImageAware;

        $dispatcher->addListener($this->event, array($this, 'apply'));
    }

}
