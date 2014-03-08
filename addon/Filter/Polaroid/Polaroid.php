<?php

namespace Filter\Polaroid;

use Silex\Application;
use DynImage\FilterInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Effet Polaroid
 *
 * @author pascal.roux
 */
class Polaroid implements FilterInterface {

    public $arguments;

    public function __construct($arguments = null) {
        $default_arguments = array(
            'angle' => 25,
            'random_angle' => false
        );
        if (is_null($arguments)) {
            $arguments = array();
        }
        $this->arguments = array_replace_recursive($default_arguments, $arguments);
    }

    public function connect(Request $request, Application $app) {
        $arguments = $this->arguments;

        $dynimage_arguments = $app['dynimage']->arguments;

        if ($dynimage_arguments['lib'] == 'Imagick') {
            $app['dispatcher']->addListener('dynimage.imagine', function () use ($app, $arguments) {
                $app['monolog']->addDebug('entering polaroid connect');
                if (!is_null($arguments)) {

                    $im = new \Imagick();
                    //$im = $app['dynimage.image']->getImagick();
                    $im->readImageBlob($app['dynimage.image']);
                    $angle = $arguments['angle'];
                    if ($arguments['random_angle']) {
                        $angle = rand(-45,45);
                    }
                    $im->polaroidImage(new \ImagickDraw(), $angle);
                    $app['dynimage.image'] = new \Imagine\Imagick\Image($im,$app['dynimage.image']->palette());
                }
            });
        }
    }

}