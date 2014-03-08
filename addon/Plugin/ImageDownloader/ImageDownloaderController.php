<?php

namespace Plugin\ImageDownloader;

use Silex\Application;
use Silex\ControllerProviderInterface;



class ImageDownloaderControllerProvider implements ControllerProviderInterface {

    public function connect(Application $app) {
        
        $controllers = $app['controllers_factory'];
       
        $controllers->before('DynImage\DynImage::build');
      

        $depth = $app['plugin.imageloader.depth'];
        $dir = '';
        for ($index = 0; $index < $depth; $index++) {
            $dir .= '/{dir' . $index . '}';
           
            $controllers->get('/{namespace}/{containerFilename}' . $dir . '/{imageFilename}', null);
        }

        $controllers->get('/{namespace}/{containerFilename}/{imageFilename}', null);
       
        return $controllers;
    }

}

?>