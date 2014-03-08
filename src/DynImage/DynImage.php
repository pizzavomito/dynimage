<?php

namespace DynImage;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * DynImage service
 *
 * @author pascal.roux
 */
class DynImage {

    public $arguments;

    public function __construct($arguments) {
        $default_arguments = array(
            'lib' => 'Gd'
        );
        if (is_null($arguments)) {
            $arguments = array();
        }
        $this->arguments = array_replace_recursive($default_arguments, $arguments);
        $this->arguments = $arguments;
    }

   

    /**
     * Definir le fichier image
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Silex\Application $app
     */
    static public function setting(Request $request, Application $app) {
        $arguments = $app['dynimage']->arguments;

        $app['monolog']->addDebug('entering dynimage setting imagefilename');
        if (is_null($arguments)) {

            $app->abort(404);
        }

        if (isset($arguments['enabled']) && !$arguments['enabled']) {

            $app->abort(404);
        }

        
        if (isset($arguments['image'])) {
            $app['dynimage.image.filename'] = $arguments['image'];
        }

        if (!isset($app['dynimage.image.filename'])) {
            $app['dynimage.image.filename'] = '';
        }
    }

    /**
     * Creation de l'image
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Silex\Application $app
     * 
     */
    static public function imagine(Request $request, Application $app) {
        $app['monolog']->addDebug('entering dynimage new imagine');
        $app['monolog']->addDebug('dispatch dynimage.before.imagine');
        $app['dispatcher']->dispatch('dynimage.before.imagine');
        
        $arguments = $app['dynimage']->arguments;
        
        if ($app['dynimage.status'] == 'stop') {

            return;
        }
        if (!file_exists($app['dynimage.image.filename'])) {
            if (!isset($arguments['default'])) {
                $app->abort(404, 'The image was not found.');
            }

            $app['dynimage.image.filename'] = $arguments['default'];
        }
        $class = sprintf('\Imagine\%s\Imagine', $arguments['lib']);
        $app['monolog']->addDebug('imagefilename : ' . $app['dynimage.image.filename']);
        $app['imagine'] = new $class();

        $app['dynimage.image'] = $app['imagine']->load($app['imagine']->open($app['dynimage.image.filename']));
        $app['dispatcher']->dispatch('dynimage.imagine');
    }

    /**
     * Charge le bon container en fonction de l'url
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Silex\Application $app
     * 
     */
    static public function prepare(Request $request, Application $app) {
        $app['monolog']->addDebug('entering dynimage prepare');
        $app['dynimage.status'] = 'start';


        if (isset($app['dynimage.container'])) {
            return;
        }

        $namespace = $request->attributes->get('namespace');
        $containerFilename = $request->attributes->get('containerFilename');


        $app['dynimage.container_loader']->load($namespace, $containerFilename, $app);


        $app['dynimage'] = $app['dynimage.container']->get('dynimage');
        $app['dynimage.arguments'] = $app['dynimage']->arguments;
    }

    /**
     * Connecte tous les filters et plugins du container
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Silex\Application $app
     */
    static public function build(Request $request, Application $app) {

        $app['monolog']->addDebug('entering dynimage build');

        $ids = $app['dynimage.container']->getServiceIds();

        foreach ($ids as $id => $value) {
            if (is_numeric($value)) {

                $service = $app['dynimage.container']->get($value);

                if ($value != 'service_container' && $value != 'dynimage') {
                   
                    $app['monolog']->addDebug(get_class($service) .' connecting');
                    $service->connect($request, $app);
                }
            }
        }

   
    }

    static public function response(Request $request, Response $response, Application $app) {

        $app['monolog']->addDebug('entering dynimage send response');

        if ($app['dynimage.status'] == 'stop') {

            return;
        }
        if (!isset($app['dynimage.image'])) {

            $app->abort(404);
        }

        if (isset($app['dynimage.arguments']['format'])) {
            $format = $app['dynimage.arguments']['format'];
        } else {
            $format = pathinfo($app['dynimage.image.filename'], PATHINFO_EXTENSION);
        }

        $response->headers->set('Content-Type', 'image/' . $format);
        $response->setContent($app['dynimage.image']->get($format));

        $response->setPublic();
        //$response->setStatusCode(200);
        if (isset($app['dynimage.arguments']['time-to-live'])) {

            $response->setTtl($app['dynimage.arguments']['time-to-live']);
        }
        //$app['monolog']->addInfo('< ' . $response->getStatusCode());
        return $response;
    }

}

