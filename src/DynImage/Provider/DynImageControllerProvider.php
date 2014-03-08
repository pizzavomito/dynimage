<?php

namespace DynImage\Provider;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use DynImage\Plugins;
use DynImage\DynImage;

/**
 * Le controller du dynimage
 */
class DynImageControllerProvider implements ControllerProviderInterface {

    public function connect(Application $app) {
        
        Plugins::init($app);
       
        $controllers = $app['controllers_factory'];
        
        $controllers->before('DynImage\DynImage::prepare')
                    ->before('DynImage\DynImage::build')
                    ->before('DynImage\DynImage::setting')
                    ->before('DynImage\DynImage::imagine');
        
        if (!isset($app['dynimage']['routes']))  {
            $app['dynimage']['routes'] = array(
                'main' => '/{namespace}/{containerFilename}',

            );
        } 
        
        foreach ($app['dynimage']['routes'] as $route) {
            
            $controllers->get($route,  function (Request $request) use ($app) {
                   
                    $app['monolog']->addDebug('entering dynimage controller');
                    $response = new Response;
                    return DynImage::response($request,$response,$app);
            });
        }

        return $controllers;
    }

}