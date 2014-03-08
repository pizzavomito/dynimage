<?php

namespace Plugin\ImageDownloader;

use Silex\Application;
use DynImage\PluginInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;


/**
 * Description of ImageDownloader
 *
 * @author pascal.roux
 */
class ImageDownloader implements PluginInterface {

    public $arguments;

    public function __construct($arguments = null) {
        
        if (is_null($arguments)) {
            $arguments = array();
        }
        $this->arguments = $arguments;


    }

    public function init(Application $app, $plugins) {

        
    }

    
   public function connect(Request $request,Application $app) {
        
        $app['monolog']->addDebug('entering imageDownloader connect');
        $arguments = $this->arguments;
        
        if (isset($arguments['enabled']) && $arguments['enabled'] == false) {
            
            return;
        }

        $app['dispatcher']->addListener('dynimage.before.imagine', function () use ($app, $arguments,$request) {
        //$app->after(function (Request $request, Response $response) use ($app, $arguments) {

                   $app['monolog']->addDebug('entering imagedownloader ');
                   /**/  
                   if (isset($app['dynimage.arguments']['format'])) {
                        $format = $app['dynimage.arguments']['format'];
                    } else {
                        $format = pathinfo($app['dynimage.image.filename'], PATHINFO_EXTENSION);
                    }
                    /**/
                    //return $app->sendFile($app['dynimage.image.filename'])
                    //->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, basename($app['dynimage.image.filename']));

                    /**/
                    $response = new Response();
                    $response->headers->set('Content-Type', 'image/' . $format);
                    
                    $response->setContent(file_get_contents($app['dynimage.image.filename']));
                    $response->headers->set('Content-Disposition', 'attachment; filename="'.basename($app['dynimage.image.filename']).'"');

                    $response->setPublic();
                    $response->setStatusCode(200);
                   
                    $app['dynimage.status'] = 'stop';
                   
                 
             
                    return $response;
                    /**/
               // }, 1024);
        });
    }

}

?>