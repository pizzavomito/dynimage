<?php

namespace DynImage;

use Symfony\Component\EventDispatcher\EventDispatcher;
use DynImage\Transformer;


class DynImage {

    static public function createImage(Transformer $transformer, $imageContent, $imagefilename, $parameters = array(), $driver = null) {

        $imageManager = new ImageManager();
        $imageManager->imagefilename = $imagefilename;

        $dispatcher = new EventDispatcher();

        $filters = $transformer->getFilters();

        if (!empty($filters)) {
            foreach ($filters as $filter) {

                $filter->connect($imageManager, $dispatcher, $parameters);
            }
        }

        $dispatcher->dispatch(Events::BEFORE_CREATE_IMAGE);

        if (is_null($driver)) {
            if (class_exists('\Gmagick')) {
                $driver = 'Gmagick';
            } elseif (class_exists('\Imagick')) {
                $driver = 'Imagick';
            } else {
                $driver = 'Gd';
            }
        }
        
        $class = sprintf('\Imagine\%s\Imagine', $driver);

        $imageManager->imagine = new $class();

        $imageManager->image = $imageManager->imagine->load($imageContent);

        $dispatcher->dispatch(Events::AFTER_CREATE_IMAGE);

        $dispatcher->dispatch(Events::EARLY_APPLY_FILTER);

        $dispatcher->dispatch(Events::LATE_APPLY_FILTER);

        $dispatcher->dispatch(Events::FINISH_CREATE_IMAGE);



        return $imageManager;
    }

    static public function getImage(Transformer $transformer, $imageContent, $imagefilename, $lib = 'Gd', $parameters = array()) {
        $imageManager = self::createImage($transformer, $imageContent, $imagefilename, $lib, $parameters);

        return $imageManager->image;
    }

   
}
