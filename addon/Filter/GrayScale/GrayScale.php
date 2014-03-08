<?php

namespace Filter\GrayScale;

use Silex\Application;
use DynImage\FilterInterface;
use Symfony\Component\HttpFoundation\Request;


/**
 * Noir et blanc
 *
 * @author pascal.roux
 */
class GrayScale implements FilterInterface {

    public $arguments;

    public function __construct() {
        
    }

    public function connect(Request $request, Application $app) {

        

        $app['dispatcher']->addListener('dynimage.imagine', function () use ($app) {

            $app['monolog']->addDebug('entering grayscale connect');

            $app['dynimage.image']->effects()->grayscale();
            
        });
    }

}