<?php

namespace Filter\Gamma;

use Silex\Application;
use DynImage\FilterInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Gamma correction
 *
 * @author pascal.roux
 */
class Gamma implements FilterInterface {

    public $arguments;

    public function __construct($arguments) {
        $this->arguments = $arguments;
    }

    public function connect(Request $request, Application $app) {

        $arguments = $this->arguments;

        $app['dispatcher']->addListener('dynimage.imagine', function () use ($app, $arguments) {

            $app['monolog']->addDebug('entering gamma connect');

            if (!is_null($arguments)) {

                $app['dynimage.image']->effects()->gamma($arguments['correction']);
            }
        });
    }

}