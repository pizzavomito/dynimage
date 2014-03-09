<?php

namespace Plugin\ImageDownloader;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Silex\ControllerProviderInterface;



class ImageDownloaderControllerProvider implements ControllerProviderInterface {

    public function connect(Application $app) {
        
        $controllers = $app['controllers_factory'];
       
        $controllers->before('DynImage\DynImage::boot');
        $controllers->after('Plugin\ImageDownloader\ImageDownloader::terminate');
        //$controllers->after('DynImage\DynImage::terminate');
      

        $depth = 10;
        //$depth = $app['dynimage.arguments']['plugin.imageloader.depth'];
        $dir = '';
        for ($index = 0; $index < $depth; $index++) {
            $dir .= '/{dir' . $index . '}';

            $controllers->get('/download/{namespace}/{containerFilename}' . $dir . '/{imageFilename}', function (Request $request) use ($app) {
                $response = new Response;
                $app['monolog']->addDebug('entering imagedownloader controller');
                //return DynImage::terminate($request, $response, $app);
                return $response;
            });
        }

        $controllers->get('/download/{namespace}/{containerFilename}/{imageFilename}', function (Request $request) use ($app) {

            $response = new Response;
            $app['monolog']->addDebug('entering imagedownloader controller');
            return $response;
        });
       
        return $controllers;
    }

}