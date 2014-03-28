<?php

namespace Filter\RoundCorners;

use Silex\Application;
use DynImage\FilterInterface;
use Symfony\Component\HttpFoundation\Request;
use DynImage\Events;
/**
 * Description of RoundCorners
 *
 * @author pascal.roux
 */
class RoundCorners implements FilterInterface {

    public $arguments;

    public function __construct($arguments = null) {
        error_log('entering round construct');
        $default_arguments = array(
            'x' => 5,
            'y' => 3
        );
        if (is_null($arguments)) {
            $arguments = array();
        }
        $this->arguments = array_replace_recursive($default_arguments, $arguments);
    }

    public function connect(Request $request, Application $app) {
        $arguments = $this->arguments;


        $dynimage_arguments = $app['dynimage.module']->get('imagerequest')->arguments;

        if ($dynimage_arguments['lib'] == 'Imagick') {
            $app['dispatcher']->addListener(Events::AFTER_CREATE_IMAGE, function () use ($app, $arguments) {
                $app['monolog']->addDebug('entering roundcorner connect');
                error_log('entering roundcorner connect');
                //$im = $app['dynimage.module']->get('imagerequest')->image->getImagick();
                //$im = new \Imagick();
                
                //$im->readImageBlob($app['dynimage.module']->get('imagerequest')->image);
                $app['dynimage.module']->get('imagerequest')->image->getImagick()->roundCorners($arguments['x'], $arguments['y']);
                
                //$app['dynimage.module']->get('imagerequest')->image = new \Imagine\Imagick\Image($im,$app['dynimage.module']->get('imagerequest')->image->palette());


            });
        }
    }

}
