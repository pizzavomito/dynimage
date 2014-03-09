<?php

namespace DynImage;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * 
 *
 * @author pascal.roux
 */
class DynImage {

    static public function init(Request $request, Application $app) {
        
    }
    static public function boot(Request $request, Application $app) {
        
        $app['monolog']->addDebug('entering dynimage boot');
        $app['dynimage.status'] = 'start';

        if (isset($app['dynimage.container'])) {
            return;
        }

        $namespace = $request->attributes->get('namespace');
        $containerFilename = $request->attributes->get('containerFilename');

        $containerLoader = new ContainerLoader($app['dynimage']['container_dir'],$app['dynimage']['cache_dir']);

        $container = $containerLoader->load($namespace, $containerFilename, $app);
        $app['dynimage.container'] = $container;
                
        $imageRequest = $container->get('imagerequest');
        $app['monolog']->addDebug('lib:'.$imageRequest->arguments['lib']);
        
         
        ///////////////////////
        $app['monolog']->addDebug('entering dynimage connecting service');

        $ids = $container->getServiceIds();

        foreach ($ids as $id => $value) {
            if (is_numeric($value)) {

                $service = $container->get($value);

                if ($value != 'service_container' && $value != 'dynimage') {
                   
                    $app['monolog']->addDebug(get_class($service) .' connecting');
                    $service->connect($request, $app);
                }
            }
        }
        
        
        ///////////////////////////
      

       
        if (is_null($imageRequest->arguments)) {

            $app->abort(404);
        }

        if (isset($imageRequest->arguments['enabled']) && !$imageRequest->arguments['enabled']) {

            $app->abort(404);
        }

        
        if (isset($imageRequest->arguments['image'])) {
            
            $imageRequest->imagefilename = $imageRequest->arguments['image'];
        }

        if (!isset($imageRequest->imagefilename)) {
            $imageRequest->imagefilename = '';
        }
        
        ///////////////////////////
        
        
        $app['monolog']->addDebug('dispatch '.Events::BEFORE_CREATE_IMAGE);
        $app['dispatcher']->dispatch(Events::BEFORE_CREATE_IMAGE);
 
        if ($app['dynimage.status'] == 'stop') {
            return;
        }
        
        $app['monolog']->addDebug('entering dynimage create image');
        if (!file_exists($imageRequest->imagefilename)) {
            if (!isset($imageRequest->arguments['default'])) {
                $app->abort(404, 'The image was not found.');
            }

            $imageRequest->imagefilename = $imageRequest->arguments['default'];
        }
        $class = sprintf('\Imagine\%s\Imagine', $imageRequest->arguments['lib']);
        $app['monolog']->addDebug('imagefilename : ' . $imageRequest->imagefilename);
        $imageRequest->imagine = new $class();

        $imageRequest->image = $imageRequest->imagine->load($imageRequest->imagine->open($imageRequest->imagefilename));
        $app['monolog']->addDebug('dispatch '.Events::AFTER_CREATE_IMAGE);
        $app['dispatcher']->dispatch(Events::AFTER_CREATE_IMAGE);
    }

    

    static public function terminate(Request $request, Response $response, Application $app) {
        
        $app['monolog']->addDebug('entering dynimage terminate');
        $imageRequest = $app['dynimage.container']->get('imagerequest');        
       
        if (!isset($imageRequest->image)) {

            $app->abort(404);
        }

        if (isset($imageRequest->arguments['format'])) {
            $format = $imageRequest->arguments['format'];
        } else {
            $format = pathinfo($imageRequest->imagefilename, PATHINFO_EXTENSION);
        }

        $response->headers->set('Content-Type', 'image/' . $format);
        $response->setContent($imageRequest->image->get($format));

        $response->setPublic();
        //$response->setStatusCode(200);
        if (isset($imageRequest->arguments['time-to-live'])) {

            $response->setTtl($imageRequest->arguments['time-to-live']);
        }
        
        return $response;
        
    }

}

