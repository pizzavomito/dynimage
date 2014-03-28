<?php

namespace Filter\Gamma;

use Silex\Application;
use DynImage\FilterInterface;
use Symfony\Component\HttpFoundation\Request;
use DynImage\Events;
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

        $app['dispatcher']->addListener(Events::AFTER_CREATE_IMAGE, function () use ($app, $arguments) {

            $app['monolog']->addDebug('entering gamma connect');

            if (!is_null($arguments)) {

                $app['dynimage.module']->get('imagerequest')->image->effects()->gamma($arguments['correction']);
            }
        });
    }

}