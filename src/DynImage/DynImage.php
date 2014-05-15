<?php

namespace DynImage;

use Symfony\Component\EventDispatcher\EventDispatcher;

class DynImage {

    public $image;
    public $imagine;
    private $driver = null;
    private $filters = array();

    public function getFilters() {
        return $this->filters;
    }

    public function add(FilterInterface $filter, $event = null) {

        if (!is_null($event)) {
            $filter->setEvent($event);
        }

        $this->filters[] = $filter;
    }

    public function setDriver($driver = null) {
        if (is_null($driver) && is_null($this->driver)) {
            if (class_exists('\Gmagick')) {
                $this->driver = 'Gmagick';
            } elseif (class_exists('\Imagick')) {
                $this->driver = 'Imagick';
            } else {
                $this->driver = 'Gd';
            }
        } else {
            $this->driver = $driver;
        }
    }

    public function getDriver() {
        return $this->driver;
    }

    public function apply($imageContent, $driver = null) {

        if (empty($this->filters)) {
            return;
        }

        $this->setDriver($driver);

        $dispatcher = new EventDispatcher();

        $class = sprintf('\Imagine\%s\Imagine', $this->driver);

        $this->imagine = new $class();

        $this->image = $this->imagine->load($imageContent);

        foreach ($this->filters as $filter) {

            $filter->connect($this, $dispatcher);
        }

        $dispatcher->dispatch(Events::AFTER_CREATE_IMAGE);

        $dispatcher->dispatch(Events::EARLY_APPLY_FILTER);

        $dispatcher->dispatch(Events::LATE_APPLY_FILTER);

        $dispatcher->dispatch(Events::FINISH_CREATE_IMAGE);

       // $this->image->strip();

        return $this->image;
    }

}
