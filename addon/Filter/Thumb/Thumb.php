<?php

namespace Filter\Thumb;

use Silex\Application;
use DynImage\FilterInterface;
use Symfony\Component\HttpFoundation\Request;
use Imagine\Image\Box;
use DynImage\Events;
/**
 * Creation de miniature
 *
 * @author pascal.roux
 */
class Thumb implements FilterInterface {

    public $arguments;

    public function __construct($arguments) {
        $this->arguments = $arguments;
    }

    public function connect(Request $request, Application $app) {

        $arguments = $this->arguments;

        $app['dispatcher']->addListener(Events::AFTER_CREATE_IMAGE, function () use ($app, $arguments) {

            $app['monolog']->addDebug('entering thumb connect');

            if (!is_null($arguments)) {

                $app['dynimage.module']->get('imagerequest')->image = $app['dynimage.module']->get('imagerequest')->image->thumbnail(new Box($arguments['width'], $arguments['height']));
            }
        });
    }

}