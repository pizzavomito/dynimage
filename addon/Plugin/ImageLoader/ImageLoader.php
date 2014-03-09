<?php

namespace Plugin\ImageLoader;

use Silex\Application;
use DynImage\PluginInterface;
use Symfony\Component\HttpFoundation\Request;


/**
 * Description of ImageLoader
 *
 * @author pascal.roux
 */
class ImageLoader implements PluginInterface {

    public $arguments;

    public function __construct($arguments = null) {

        if (is_null($arguments)) {
            $arguments = array();
        }
        $this->arguments = $arguments;
    }

    public function init(Application $app, $plugins) {
        $app['monolog']->addDebug('entering imageloader init');
        $app['plugin.imageloader.depth'] = $plugins->getParameter('plugin.imageloader.depth');
        $app->mount('/' . $app['dynimage']['routes_prefix'], new ImageLoaderControllerProvider());
    }

    public function connect(Request $request, Application $app) {
        $app['monolog']->addDebug('entering imageloader connect');
        $arguments = $this->arguments;

        if (isset($arguments['enabled']) && $arguments['enabled'] == false) {

            return;
        }


        $app['monolog']->addDebug('entering imageloader setting imagefilename');
        if (!is_null($arguments)) {

            $depth = $app['plugin.imageloader.depth'];

            $imageFilename = '';
            for ($index = 0; $index < $depth; $index++) {
                $dir = $request->attributes->get('dir' . $index);
                if (!is_null($dir)) {
                    $imageFilename .= $request->attributes->get('dir' . $index) . '/';
                }
            }


            $imageFilename .= $request->attributes->get('imageFilename');

            $path = $arguments['path'];


            if (!file_exists($path . $imageFilename)) {
                if (!isset($arguments['default'])) {


                    $app->abort(404, 'The image was not found.');
                }
                $app['dynimage.container']->get('imagerequest')->imagefilename = $arguments['default'];
                //$app['dynimage.image.filename'] = $arguments['default'];
            } else {
                //$app['monolog']->addDebug($path . $imageFilename);
                //$app['dynimage.image.filename'] = $path . $imageFilename;
                $app['dynimage.container']->get('imagerequest')->imagefilename = $path . $imageFilename;
            }
        }
    }


}
