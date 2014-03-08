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

        $controllers->before('DynImage\DynImage::prepare')
                ->before('DynImage\DynImage::build')
                ->before('DynImage\DynImage::setting')
                ->before('DynImage\DynImage::imagine');


        $depth = $app['plugin.imageloader.depth'];
        //$depth = $app['dynimage.arguments']['plugin.imageloader.depth'];
        $dir = '';
        for ($index = 0; $index < $depth; $index++) {
            $dir .= '/{dir' . $index . '}';

            $controllers->get('/{namespace}/{containerFilename}' . $dir . '/{imageFilename}', function (Request $request) use ($app) {
                $response = new Response;
                $app['monolog']->addDebug('entering imageloader controller');
                return DynImage::response($request, $response, $app);
            });

            
        }

        $controllers->get('/{namespace}/{containerFilename}/{imageFilename}', function (Request $request) use ($app) {

            $response = new Response;
            $app['monolog']->addDebug('entering imageloader controller');
            return DynImage::response($request, $response, $app);
        });


        return $controllers;
    }

}
