<?php

namespace Plugin\ImageLoader;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use DynImage\DynImage;

/**
 * 
 */
class ImageLoaderControllerProvider implements ControllerProviderInterface {

    public function connect(Application $app) {
        $app['monolog']->addDebug('entering imageloadercontroller connect');
        $controllers = $app['controllers_factory'];

        $controllers->before('DynImage\DynImage::boot');
        $controllers->after('DynImage\DynImage::terminate');


        $depth = $app['plugin.imageloader.depth'];
        //$depth = $app['dynimage.arguments']['plugin.imageloader.depth'];
        $dir = '';
        for ($index = 0; $index < $depth; $index++) {
            $dir .= '/{dir' . $index . '}';

            $controllers->get('/{package}/{module}' . $dir . '/{imageFilename}', function (Request $request) use ($app) {
                $response = new Response;
                $app['monolog']->addDebug('entering imageloader controller');
                //return DynImage::terminate($request, $response, $app);
                return $response;
            });
        }

        $controllers->get('/{package}/{module}/{imageFilename}', function (Request $request) use ($app) {

            $response = new Response;
            $app['monolog']->addDebug('entering imageloader controller');
            return $response;
        });


        return $controllers;
    }

}
