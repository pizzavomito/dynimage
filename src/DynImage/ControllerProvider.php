<?php

namespace DynImage;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;



/**
 * Le controller du dynimage
 */
class ControllerProvider implements ControllerProviderInterface {

    public function connect(Application $app) {

        Plugins::init($app);

        $controllers = $app['controllers_factory'];

        $controllers->before('DynImage\DynImage::boot');
        $controllers->after('DynImage\DynImage::terminate');
        
        if (!isset($app['dynimage']['routes'])) {
            $app['dynimage']['routes'] = array(
                'main' => '/{namespace}/{containerFilename}',
            );
        }

        foreach ($app['dynimage']['routes'] as $route) {

            $controllers->get($route, function (Request $request) use ($app) {

                $app['monolog']->addDebug('entering dynimage controller');
                return new Response;
                //return DynImage::terminate($request, $response, $app);
            });
        }

        return $controllers;
    }

}
