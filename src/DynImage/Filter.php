<?php

namespace DynImage;

class Filter {

    protected $arguments;
    protected $imageManager;
    protected $options;

    public function __construct($arguments = null) {
        if (is_null($arguments)) {
            $arguments = array();
        }
        $this->arguments = array_replace_recursive($this->default_arguments, $arguments);
    }

    public function setEvent($event) {

        $this->event = $event;
    }

    public function connect($imageManager, $dispatcher, $options) {

        $this->options = $options;

        $this->imageManager = $imageManager;

        $dispatcher->addListener($this->event, array($this, 'apply'));
    }

    public function getArguments() {
        return $this->arguments;
    }


}
