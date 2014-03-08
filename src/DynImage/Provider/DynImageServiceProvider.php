<?php

namespace DynImage\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use DynImage\Loader\ContainerLoader;
use DynImage\Loader\PluginLoader;

/**
 * Enregistre les services dans l'application
 */
class DynImageServiceProvider implements ServiceProviderInterface {

    public function register(Application $app) {

        $app['dynimage.container_loader'] = $app->share(function () use ($app) {

            return new ContainerLoader($app['dynimage.container_dir'], $app['dynimage.cache_dir']);
        });

        $app['dynimage.plugin_loader'] = $app->share(function () use ($app) {
            return new PluginLoader($app['dynimage.plugin_dir'], $app['dynimage.cache_dir']);
        });
    }

    public function boot(Application $app) {
        
    }

}
