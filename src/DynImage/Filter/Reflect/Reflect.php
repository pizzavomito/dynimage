<?php

namespace Filter\Reflect;

use Silex\Application;
use DynImage\FilterInterface;
use Symfony\Component\HttpFoundation\Request;
use Imagine\Image\Box;
use Imagine\Image\Color;
use Imagine\Image\Point;
use Imagine\Image\Fill\Gradient\Vertical;
use DynImage\Events;
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
        $app['dispatcher']->addListener(Events::AFTER_CREATE_IMAGE, function () use ($app, $arguments) {
            $app['monolog']->addDebug('entering reflect connect');

            $size = $app['dynimage.module']->get('imagerequest')->image->getSize();
            $canvas = new Box($size->getWidth(), $size->getHeight() * 2);
            
            $palette = new \Imagine\Image\Palette\RGB();
            $white = $palette->color($arguments['color']);
           
            $fill = new Vertical(
                    $size->getHeight(), $white->darken(127), $white
            );

            $tr = $app['dynimage.module']->get('imagerequest')->imagine->create($size)
                    ->fill($fill);

            $reflection = $app['dynimage.module']->get('imagerequest')->image->copy()
                    ->flipVertically()
                    ->applyMask($tr)
            ;
            $palette = new \Imagine\Image\Palette\RGB();
            $color = $palette->color($arguments['color']);
            
            $app['dynimage.module']->get('imagerequest')->image = $app['dynimage.module']->get('imagerequest')->imagine->create($canvas, $color)
                    ->paste($app['dynimage.module']->get('imagerequest')->image, new Point(0, 0))
                    ->paste($reflection, new Point(0, $size->getHeight()));
        });
    }

}
