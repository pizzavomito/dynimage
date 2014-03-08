<?php

namespace Filter\Reflect;

use Silex\Application;
use DynImage\FilterInterface;
use Symfony\Component\HttpFoundation\Request;
use Imagine\Image\Box;
use Imagine\Image\Color;
use Imagine\Image\Point;
use Imagine\Image\Fill\Gradient\Vertical;

/**
 * Effet Reflection
 *
 * @author pascal.roux
 */
class Reflect implements FilterInterface {

    public $arguments;

    public function __construct($arguments = null) {

        $default_arguments = array(
            'color' => 'ffffff'
        );
        if (is_null($arguments)) {
            $arguments = array();
        }
        $this->arguments = array_replace_recursive($default_arguments, $arguments);
    }

    public function connect(Request $request, Application $app) {
        $arguments = $this->arguments;
        $app['dispatcher']->addListener('dynimage.imagine', function () use ($app, $arguments) {
            $app['monolog']->addDebug('entering reflect connect');

            $size = $app['dynimage.image']->getSize();
            $canvas = new Box($size->getWidth(), $size->getHeight() * 2);

            $white = new Color($arguments['color']);
            $fill = new Vertical(
                    $size->getHeight(), $white->darken(127), $white
            );

            $tr = $app['imagine']->create($size)
                    ->fill($fill);

            $reflection = $app['dynimage.image']->copy()
                    ->flipVertically()
                    ->applyMask($tr)
            ;

            $app['dynimage.image'] = $app['imagine']->create($canvas, new Color($arguments['color'], 100))
                    ->paste($app['dynimage.image'], new Point(0, 0))
                    ->paste($reflection, new Point(0, $size->getHeight()));
        });
    }

}
