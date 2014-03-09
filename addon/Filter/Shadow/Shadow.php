<?php

namespace Filter\Shadow;

use Silex\Application;
use DynImage\FilterInterface;
use Symfony\Component\HttpFoundation\Request;
use DynImage\Events;

/**
 * Effet ombre portée
 *
 * @author pascal.roux
 */
class Shadow implements FilterInterface {

    public $arguments;

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

    public function connect(Request $request, Application $app) {
        $arguments = $this->arguments;


       // $dynimage_arguments = $app['dynimage.container']->get('imagerequest')->arguments;

        if ($app['dynimage.container']->get('imagerequest')->arguments['lib'] == 'Imagick') {
            $app['dispatcher']->addListener(Events::AFTER_CREATE_IMAGE, function () use ($app, $arguments) {
                $app['monolog']->addDebug('entering shadow connect');

                if (!is_null($arguments)) {

                    $palette = new \Imagine\Image\Palette\RGB();
                    $color = $palette->color($arguments['color']);
                   
                    $shadow = $app['dynimage.container']->get('imagerequest')->imagine->create($app['dynimage.container']->get('imagerequest')->image->getSize(), $color);

                    $im = new \Imagick();
                    $im->readImageBlob($shadow);
                    $im->shadowimage($arguments['opacity'], $arguments['sigma'], $arguments['x'], $arguments['y']);
                    $image = new \Imagick();
                    $image->readImageBlob($app['dynimage.container']->get('imagerequest')->image);
                    //$image = $app['dynimage.image']->getImagick();
                    $im->compositeImage($image, \Imagick::COMPOSITE_OVER, 0, 0);
                    //$image->readImageBlob($im);
                    //$app['dynimage.image'] = $im;
                    $app['dynimage.container']->get('imagerequest')->image = new \Imagine\Imagick\Image($im, $app['dynimage.container']->get('imagerequest')->image->palette());
                }
               
            });
        }
    }

}