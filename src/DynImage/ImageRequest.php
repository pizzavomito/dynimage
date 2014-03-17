<?php

namespace DynImage;


/**
 * ImageRequest
 *
 * @author pascal.roux
 */
class ImageRequest {

    public $arguments;
    public $imagefilename;
    public $image;
    public $imagine;
    
    public function __construct($arguments) {
        $default_arguments = array(
            'lib' => 'Gd'
        );
        if (is_null($arguments)) {
            $arguments = array();
        }
        $this->arguments = array_replace_recursive($default_arguments, $arguments);
       
    }

}

