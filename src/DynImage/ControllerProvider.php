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

        //Plugins::init($app['dynimage']['plugins_dir'],$app['dynimage']['cache_dir'],$app['debug'],$app);

        $controllers = $app['controllers_factory'];

        //$controllers->before('DynImage\DynImage::boot');
        $controllers->before(function(Request $request) use ($app) {
            $package = $request->attributes->get('package');
            $module = $request->attributes->get('module');
            

            $depth = 10;

            $imageFilename = '';
            for ($index = 0; $index < $depth; $index++) {
                $dir = $request->attributes->get('dir' . $index);
                if (!is_null($dir)) {
                    $imageFilename .= $request->attributes->get('dir' . $index) . '/';
                }
            }

            $imageFilename .= $request->attributes->get('imageFilename');
            
            $app['dynimage.image'] = $app['dynimage.core']->createImage($package,$module,$app['dynimage']['packages']['packager'],$imageFilename);
            
        });
        $controllers->after('DynImage\DynImage::terminate');
        
        if (!isset($app['dynimage']['routes'])) {
            $app['dynimage']['routes'] = array(
                'main' => '/{package}/{module}',
            );
        }

        foreach ($app['dynimage']['routes'] as $route) {

            $controllers->get($route, function (Request $request) use ($app) {

                $app['monolog']->addDebug('entering dynimage controller');
                return new Response;
                //return DynImage::terminate($request, $response, $app);
            });
        }
        
        $depth = 10;
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
