<?php

namespace Filter\RoundCorners;

use Silex\Application;
use DynImage\FilterInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Description of RoundCorners
 *
 * @author pascal.roux
 */
class RoundCorners implements FilterInterface {

    public $arguments;

    public function __construct($arguments = null) {
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


        $dynimage_arguments = $app['dynimage']->arguments;

        if ($dynimage_arguments['lib'] == 'Imagick') {
            $app['dispatcher']->addListener('dynimage.imagine', function () use ($app, $arguments) {


                $im = $app['dynimage.image']->getImagick();
                
                $im->roundCorners($arguments['x'], $arguments['y']);

            });
        }
    }

}
