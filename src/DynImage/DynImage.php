<?php

namespace DynImage;

use Symfony\Component\EventDispatcher\EventDispatcher;
use DynImage\Transformer;

class DynImage {

    public $image;
    public $imagine;
    public $options;

    public function add(FilterInterface $filter, $event = null) {

        if (!is_null($event)) {
            $filter->setEvent($event);
        }

        $this->filters[] = $filter;
    }

    public function apply($imageContent, $driver = null, $options = array()) {

        if (is_null($driver)) {
            if (class_exists('\Gmagick')) {
                $driver = 'Gmagick';
            } elseif (class_exists('\Imagick')) {
                $driver = 'Imagick';
            } else {
                $driver = 'Gd';
            }
        }

        $this->options = $options;
        $this->options['driver'] = $driver;

        $dispatcher = new EventDispatcher();

        if (empty($this->filters)) {
            return;
        }

        $class = sprintf('\Imagine\%s\Imagine', $driver);

        $this->imagine = new $class();

        $this->image = $this->imagine->load($imageContent);

        foreach ($this->filters as $filter) {

            $filter->connect($this, $dispatcher);
        }
        
        $dispatcher->dispatch(Events::AFTER_CREATE_IMAGE);
        
        $dispatcher->dispatch(Events::EARLY_APPLY_FILTER);

        $dispatcher->dispatch(Events::LATE_APPLY_FILTER);

        $dispatcher->dispatch(Events::FINISH_CREATE_IMAGE);
        
         
        return $this->image;
    }

}
