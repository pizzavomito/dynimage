<?php

namespace Filter\Blur;

use Silex\Application;
use DynImage\FilterInterface;
use Symfony\Component\HttpFoundation\Request;
use DynImage\Events;
/**
 * Blur 
 *
 * @author pascal.roux
 */
class Blur implements FilterInterface {

    public $arguments;

    public function __construct($arguments = null) {
        $default_arguments = array(
            'sigma' => 3
        );
        if (is_null($arguments)) {
            $arguments = array();
        }
        $this->arguments = array_replace_recursive($default_arguments, $arguments);
    }

    public function connect(Request $request, Application $app) {

        $arguments = $this->arguments;

        $app['dispatcher']->addListener(Events::AFTER_CREATE_IMAGE, function () use ($app, $arguments) {

            $app['monolog']->addDebug('entering blur connect');

            if (!is_null($arguments)) {
               
                $app['dynimage.container']->get('imagerequest')->image->effects()->blur($arguments['sigma']);
            }
        });
    }

}