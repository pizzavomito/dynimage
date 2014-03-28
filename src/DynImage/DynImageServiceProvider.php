<?php
namespace DynImage;

use Silex\Application;
use Silex\ServiceProviderInterface;
use DynImage\DynImage;

class DynImageServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['dynimage.core'] = $app->share(function () use ($app) {
            $debug = $app['debug'] ? $app['debug'] : true;
            $env = $app['env'] ? $app['env'] : '';

            return new DynImage($app['dynimage.cache_dir'],$env,$debug);
        });
    }

    public function boot(Application $app)
    {
    }
}
