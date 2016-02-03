<?php

namespace DynImage;

use Imagine\Image\AbstractImage;
use Imagine\Image\ImagineInterface;

class DynImageAware
{
    /**
     * @var AbstractImage
     */
    public $image;

    /**
     * @var ImagineInterface
     */
    public $imagine;

    private $driver = 'Gd';

    public function loadImage($imageContent)
    {
        $class = sprintf('\Imagine\%s\Imagine', $this->driver);
        $this->imagine = new $class();
        $this->image = $this->imagine->load($imageContent);
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

        return $this;
    }



    public function getDriver() {
        return $this->driver;
    }

    /**
     * @return AbstractImage
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     * @return DynImageAware
     */
    public function setImage($image)
    {
        $this->image = $image;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getImagine()
    {
        return $this->imagine;
    }

    /**
     * @param mixed $imagine
     * @return DynImageAware
     */
    public function setImagine($imagine)
    {
        $this->imagine = $imagine;
        return $this;
    }


}