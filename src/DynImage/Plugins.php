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
     
        $pluginsLoader = new PluginsLoader($app['dynimage']['plugins_dir'],$app['dynimage']['cache_dir']);
        $plugins = $pluginsLoader->load('plugins.yml', $app['debug']);

        foreach ($plugins->getServiceIds() as $id) {
            if ($id != 'service_container') {

                $plugin = $plugins->get($id);
                $plugin->init($app, $plugins);
            }
        }
    }
    
}