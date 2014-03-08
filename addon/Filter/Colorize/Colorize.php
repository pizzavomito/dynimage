<?php

namespace Filter\Colorize;

use Silex\Application;
use DynImage\FilterInterface;
use Symfony\Component\HttpFoundation\Request;

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

        $app['dispatcher']->addListener('dynimage.imagine', function () use ($app, $arguments) {

            $app['monolog']->addDebug('entering colorize connect');

            if (!is_null($arguments)) {
                $color = $app['dynimage.image']->palette()->color($arguments['color']);

                $app['dynimage.image']->effects()->colorize($color);
            }
        });
    }

}