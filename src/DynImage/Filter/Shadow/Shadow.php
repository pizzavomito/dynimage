<?php

namespace DynImage\Filter\Shadow;

use Silex\Application;
use DynImage\FilterInterface;
use Symfony\Component\HttpFoundation\Request;
use DynImage\Events;
use DynImage\Filter;

/**
 * Effet ombre portée
 *
 * @author pascal.roux
 */
class Shadow extends Filter implements FilterInterface {

    protected $event = Events::DINNER_APPLY_FILTER;

    public function __construct($arguments = null) {
        $default_arguments = array(
            'opacity' => 80,
            'x' => 5,
            'y' => 5,
            'sigma' => 3,
            'color' => '#000000'
        );
        if (is_null($arguments)) {
            $arguments = array();
        }
        $this->arguments = array_replace_recursive($default_arguments, $arguments);
    }

    public function getEvent() {
        return $this->event;
    }

    public function apply() {

        if ($this->parameters['lib'] == 'Imagick') {
            $palette = new \Imagine\Image\Palette\RGB();
            $color = $palette->color($this->arguments['color']);

            $shadow = $this->imageManager->imagine->create($this->imageManager->image->getSize(), $color);

            $im = new \Imagick();
            $im->readImageBlob($shadow);
            $im->shadowimage($this->arguments['opacity'], $this->arguments['sigma'], $this->arguments['x'], $this->arguments['y']);
            $image = new \Imagick();
            $image->readImageBlob($this->imageManager->image);
          
            $im->compositeImage($image, \Imagick::COMPOSITE_OVER, 0, 0);
            
            $this->imageManager->image = new \Imagine\Imagick\Image($im, $this->imageManager->image->palette(),$this->imageManager->imagine->getMetadataReader()->readData($im));
        }
    }

}
