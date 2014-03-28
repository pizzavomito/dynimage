<?php

namespace Filter\Colorize;

use Silex\Application;
use DynImage\FilterInterface;
use Symfony\Component\HttpFoundation\Request;
use DynImage\Events;
/**
 * Colorize 
 *
 * @author pascal.roux
 */
class Colorize implements FilterInterface {

    public $arguments;

    public function __construct($arguments) {
        $this->arguments = $arguments;
    }

    public function connect(Request $request, Application $app) {

        $arguments = $this->arguments;

        $app['dispatcher']->addListener(Events::AFTER_CREATE_IMAGE, function () use ($app, $arguments) {

            $app['monolog']->addDebug('entering colorize connect');

            if (!is_null($arguments)) {
                $color = $app['dynimage.module']->get('imagerequest')->image->palette()->color($arguments['color']);

                $app['dynimage.module']->get('imagerequest')->image->effects()->colorize($color);
            }
        });
    }

}