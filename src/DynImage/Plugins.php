<?php

namespace DynImage;

/**
 * Initialisation des plugins
 *
 * @author pascal.roux
 */

use Silex\Application;

class Plugins {
    
    static public function init($plugins_dir,$cache_dir,$debug, Application $app) {
     
        $plugins = ConfigLoader::load($plugins_dir.'plugins.yml',$cache_dir,$debug);
        
        foreach ($plugins->getServiceIds() as $id) {
            if ($id != 'service_container') {

                $plugin = $plugins->get($id);
                $plugin->init($app, $plugins);
            }
        }
    }
    
}