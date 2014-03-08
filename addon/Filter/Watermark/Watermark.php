<?php

namespace Filter\Watermark;

use Silex\Application;
use DynImage\FilterInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Description of Watermark
 *
 * @author pascal.roux
 */
class Watermark implements FilterInterface {

    public $arguments;

    public function __construct($arguments = null) {
        $default_arguments = array(
            'text' => 'Copyright',
            'font' => 'Bookman-Demi',
            'font_size' => 20,
            'font_color' => '#999999',
            'color' => '#333333',
            'position' => 'SOUTHEAST'
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
                $app['monolog']->addDebug('entering watermark connect');
                //if (!is_null($arguments)) {


                $image = $app['dynimage.image']->getImagick();
               

                $watermark = new \Imagick();
                $mask = new \Imagick();
                $draw = new \ImagickDraw();

                // Define dimensions
                $width = $image->getImageWidth();
                $height = $image->getImageHeight();

                // Create some palettes
                $watermark->newImage($width, $height, new \ImagickPixel($arguments['color']));
                $mask->newImage($width, $height, new \ImagickPixel('black'));

                // Watermark text
                $text = $arguments['text'];

                // Set font properties
                $draw->setFont($arguments['font']);
                $draw->setFontSize($arguments['font_size']);
                $draw->setFillColor($arguments['font_color']);

                // Position text
                switch (strtoupper($arguments['position'])) {
                    case 'CENTER':
                        $gravity = \Imagick::GRAVITY_CENTER;
                        break;
                    case 'EAST':
                        $gravity = \Imagick::GRAVITY_EAST;
                        break;
                    case 'NORTH':
                        $gravity = \Imagick::GRAVITY_NORTH;
                        break;
                    case 'NORTHEAST':
                        $gravity = \Imagick::GRAVITY_NORTHEAST;
                        break;
                    case 'NORTHWEST':
                        $gravity = \Imagick::GRAVITY_NORTHWEST;
                        break;
                    case 'SOUTH':
                        $gravity = \Imagick::GRAVITY_SOUTH;
                        break;
                    case 'SOUTHWEST':
                        $gravity = \Imagick::GRAVITY_SOUTHWEST;
                        break;
                    case 'SOUTHEAST':
                        $gravity = \Imagick::GRAVITY_SOUTHEAST;
                        break;
                    case 'WEST':
                        $gravity = \Imagick::GRAVITY_WEST;
                        break;
                    default:
                        $gravity = \Imagick::GRAVITY_SOUTHEAST;
                }
                $draw->setGravity($gravity);
                  
                // Draw text on the watermark palette
                $watermark->annotateImage($draw, 10, 12, 0, $text);

                // Draw text on the mask palette
                $draw->setFillColor('white');
                $mask->annotateImage($draw, 11, 13, 0, $text);
                $mask->annotateImage($draw, 10, 12, 0, $text);
                $draw->setFillColor('white');
                $mask->annotateImage($draw, 9, 11, 0, $text);

                // This is apparently needed for the mask to work
                $mask->setImageMatte(false);
                 
                // Apply mask to watermark
                $watermark->compositeImage($mask, \Imagick::COMPOSITE_COPYOPACITY, 0, 0);
                
                // Overlay watermark on image
                $image->compositeImage($watermark, \Imagick::COMPOSITE_DISSOLVE, 0, 0);

               

                //}
            });
        }
    }

}
