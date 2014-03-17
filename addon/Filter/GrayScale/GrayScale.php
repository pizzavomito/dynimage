<?php

namespace Filter\GrayScale;

use Silex\Application;
use DynImage\FilterInterface;
use Symfony\Component\HttpFoundation\Request;
use DynImage\Events;

/**
 * Noir et blanc
 *
 * @author pascal.roux
 */
class GrayScale implements FilterInterface {

    public $arguments;

    public function __construct() {
        error_log('entering grayscale construct');
    }

    public function connect(Request $request, Application $app) {

        

        $app['dispatcher']->addListener(Events::AFTER_CREATE_IMAGE, function () use ($app) {
            error_log('entering grayscale connect');
            $app['monolog']->addDebug('entering grayscale connect');

            $app['dynimage.module']->get('imagerequest')->image->effects()->grayscale();
            
        });
    }

}