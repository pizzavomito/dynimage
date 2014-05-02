<?php

namespace DynImage;

class Filter {

    protected $arguments;
    protected $imageManager;
    protected $parameters;

    public function __construct($arguments = null) {
        if (is_null($arguments)) {
            $arguments = array();
        }
        $this->arguments = array_replace_recursive($this->default_arguments, $arguments);
    }

    public function setEvent($event) {
        if (is_object($event)) {
            $event = $event->event;
        }
        $this->event = $event;
    }

    public function connect($imageManager, $dispatcher, $parameters) {

        $this->parameters = $parameters;

        $this->imageManager = $imageManager;

        if (is_array($this->default_arguments)) {
            foreach ($this->default_arguments as $key => $value) {

                if (isset($this->parameters[$this->prefix_parameter . $key])) {
                    $this->arguments[$key] = $this->parameters[$this->prefix_parameter . $key];
                }
            }
        }
        $dispatcher->addListener($this->event, array($this, 'apply'));
    }

    public function getArguments() {
        return $this->arguments;
    }

    public function getPrefixParameter() {
        return $this->prefix_parameter;
    }

}
