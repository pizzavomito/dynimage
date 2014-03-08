<?php

namespace DynImage;

/**
 * Initialisation des plugins
 *
 * @author pascal.roux
 */

use Silex\Application;

class Plugins {
    
    static public function init(Application $app) {
     
        $plugins = $app['dynimage.plugin_loader']->load('plugins.yml', $app['debug']);

        foreach ($plugins->getServiceIds() as $id) {
            if ($id != 'service_container') {

                $plugin = $plugins->get($id);
                $plugin->init($app, $plugins);
            }
        }
    }
    
}