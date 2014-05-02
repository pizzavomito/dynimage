<?php

namespace DynImage\Filter\Watermark;

use DynImage\FilterInterface;
use DynImage\Filter;
use DynImage\Events;

/**
 * Description of Watermark
 *
 * @author pascal.roux
 */
class Watermark extends Filter implements FilterInterface {

    protected $prefix_parameter ='watermark.';
    
    protected $event = Events::AFTER_CREATE_IMAGE;

    protected $default_arguments = array(
            'text' => 'Copyright',
            'font' => 'DejaVu-Sans-Book',
            'font_size' => 15,
            'font_color' => '#999999',
            'color' => '#333333',
            'position' => 'SOUTHEAST'
        );

   
    public function apply() {
        if ($this->parameters['lib'] == 'Imagick') {
           
            $image = $this->imageManager->image->getImagick();


            $watermark = new \Imagick();
            $mask = new \Imagick();
            $draw = new \ImagickDraw();

            // Define dimensions
            $width = $image->getImageWidth();
            $height = $image->getImageHeight();

            // Create some palettes
            $watermark->newImage($width, $height, new \ImagickPixel($this->arguments['color']));
            $mask->newImage($width, $height, new \ImagickPixel('black'));

            // Watermark text
            $text = $this->arguments['text'];

            // Set font properties
            $draw->setFont($this->arguments['font']);
            $draw->setFontSize($this->arguments['font_size']);
            $draw->setFillColor($this->arguments['font_color']);

            // Position text
            switch (strtoupper($this->arguments['position'])) {
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
        }
    }

}
