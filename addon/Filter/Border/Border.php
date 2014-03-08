<?php

namespace Filter\Border;

use Silex\Application;
use DynImage\FilterInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Ajoute une bordure
 *
 * @author pascal.roux
 */
class Border implements FilterInterface {

    public $arguments;

    public function __construct($arguments = null) {
        $default_arguments = array(
            'color' => '000000',
            'height' => 5,
            'width' => 5,
        );
        if (is_null($arguments)) {
            $arguments = array();
        }
        $this->arguments = array_replace_recursive($default_arguments, $arguments);
    }

    public function connect(Request $request, Application $app) {

        $arguments = $this->arguments;
        $app['dispatcher']->addListener('dynimage.imagine', function () use ($app, $arguments) {
            
            $app['monolog']->addDebug('entering border connect');
            if (!is_null($arguments)) {

                $color = new \Imagine\Image\Color($arguments['color']);
                $c = new \Imagine\Filter\Advanced\Border($color, $arguments['width'], $arguments['height']);

                $app['dynimage.image'] = $c->apply($app['dynimage.image']);
            }
            
        });
    }

}
