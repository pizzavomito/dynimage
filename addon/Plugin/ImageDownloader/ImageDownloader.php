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
         $app->mount('/' . $app['dynimage']['routes_prefix'], new ImageDownloaderControllerProvider());
    }

    public function connect(Request $request, Application $app) {

        $app['monolog']->addDebug('entering imagedownloader connect');
        $arguments = $this->arguments;

        if (isset($arguments['enabled']) && $arguments['enabled'] == false) {

            return;
        }


        $app['monolog']->addDebug('entering imagedownloader setting imagefilename');
        if (!is_null($arguments)) {

            $depth = 10;

            $imageFilename = '';
            for ($index = 0; $index < $depth; $index++) {
                $dir = $request->attributes->get('dir' . $index);
                if (!is_null($dir)) {
                    $imageFilename .= $request->attributes->get('dir' . $index) . '/';
                }
            }


            $imageFilename .= $request->attributes->get('imageFilename');

            $path = $arguments['path'];


            if (!file_exists($path . $imageFilename)) {
                if (!isset($arguments['default'])) {


                    $app->abort(404, 'The image was not found.');
                }
                $app['dynimage.module']->get('imagerequest')->imagefilename = $arguments['default'];
                
            } else {
                
                $app['dynimage.module']->get('imagerequest')->imagefilename = $path . $imageFilename;
            }
            
        }
    }

    static public function terminate(Request $request, Response $response, Application $app) {
        $app['monolog']->addDebug('entering imagedownloader terminate');
    
        return $app->sendFile($app['dynimage.module']->get('imagerequest')->imagefilename)
                ->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, basename($app['dynimage.module']->get('imagerequest')->imagefilename));
        //$response->headers->set('Content-Disposition', 'attachment; filename="' . basename($app['dynimage.module']->get('imagerequest')->imagefilename) . '"');

    }

}
