<?php

namespace DynImage;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * 
 *
 * @author pascal.roux
 */
class DynImage {

    private $cache_dir;
    private $env;
    private $debug;
    
    private $module;
    
    public function __construct($cache_dir,$env, $debug) {
        $this->cache_dir = $cache_dir;
        $this->env = $env;
        $this->debug = $debug;
    }
    
    public function getModule() {
        return $this->module;
    }
    
    public function createImage($package, $moduleName, $packager, $imagefilename = null) {
        if (is_dir($packager)) {
            $this->module = ModuleLoader::loadFromDir($moduleName, $packager . $package, $this->cache_dir, $this->env, $this->debug);
        } else {
            $packagerLoaded = ConfigLoader::load($packager . '.' . $this->env . '.yml', $this->cache_dir, $this->debug);

            $packages = $packagerLoaded->getParameter('packages');
            if (!isset($packages[$package])) {
                throw new \InvalidArgumentException(
                sprintf("The package '%s' does not exist.", $package));
            }

            if (!isset($packages[$package]['modules'][$moduleName])) {
                throw new \InvalidArgumentException(
                sprintf("The module '%s' does not exist.", $moduleName));
            }

            if (isset($packages[$package]['enabled']) && !$packages[$package]['enabled']) {
                throw new \InvalidArgumentException(
                sprintf("The package '%s' is disabled.", $package));
                // 404
            }

            $moduleFilename = $packages[$package]['modules'][$moduleName];

            $this->module = ModuleLoader::loadFromFile($moduleFilename, $package, $this->cache_dir, $this->env, $this->debug);
        }

        $dynimage = $this->module->get('dynimage');
     

        $imageRequest = $this->module->get('imagerequest');
        if (is_null($imageRequest->arguments)) {
            throw new \InvalidArgumentException();
        }

        if (isset($imageRequest->arguments['enabled']) && !$imageRequest->arguments['enabled']) {
            throw new NotFoundHttpException();
        }

        if (!is_null($imagefilename)) {
            $imageRequest->imagefilename = $imageRequest->arguments['images_dir'].$imagefilename;
        }

        if (isset($imageRequest->arguments['image'])) {

            $imageRequest->imagefilename = $imageRequest->arguments['image'];
        }
        

        if (!isset($imageRequest->imagefilename)) {
            $imageRequest->imagefilename = '';
        }
        
        $this->module->get('dispatcher')->dispatch(Events::BEFORE_CREATE_IMAGE);
        
        
        if (!file_exists($imageRequest->imagefilename)) {
            if (!isset($imageRequest->arguments['default'])) {
               
                throw new NotFoundHttpException();
            }

            $imageRequest->imagefilename = $imageRequest->arguments['default'];
        }
        $class = sprintf('\Imagine\%s\Imagine', $imageRequest->arguments['lib']);
        
        $imageRequest->imagine = new $class();

        $imageRequest->image = $imageRequest->imagine->load($imageRequest->imagine->open($imageRequest->imagefilename));
        
        $imageRequest->palette = $imageRequest->image->palette();
       
        $this->module->get('dispatcher')->dispatch(Events::AFTER_CREATE_IMAGE);
        
        
        return $imageRequest;
    }

    static public function boot(Request $request, Application $app) {

        $app['monolog']->addDebug('entering dynimage boot');

        if (isset($app['dynimage.module'])) {
            return;
        }

        $package = $request->attributes->get('package');
        $module = $request->attributes->get('module');

        /**/
        if (isset($app['dynimage']['packages']['packager'])) {

            $packager = ConfigLoader::load($app['dynimage']['packages']['packager'] . '.' . $app['env'] . '.yml', $app['dynimage']['cache_dir'], $app['debug']);

            $packages = $packager->getParameter('packages');
            if (!isset($packages[$package])) {
                throw new \InvalidArgumentException(
                sprintf("The package '%s' does not exist.", $package));
            }

            if (!isset($packages[$package]['modules'][$module])) {
                throw new \InvalidArgumentException(
                sprintf("The module '%s' does not exist.", $module));
            }

            if (isset($packages[$package]['enabled']) && !$packages[$package]['enabled']) {
                $app->abort(404);
            }

            $moduleFilename = $packages[$package]['modules'][$module];

            $moduleLoaded = ModuleLoader::loadFromFile($moduleFilename, $package, $app['dynimage']['cache_dir'], $app['env'], $app['debug']);
        } else {
            $moduleLoaded = ModuleLoader::loadFromDir($module, $app['dynimage']['packages']['dir'] . $package, $app['dynimage']['cache_dir'], $app['env'], $app['debug']);
        }
        /**/


        $app['dynimage.module'] = $moduleLoaded;

        $imageRequest = $moduleLoaded->get('imagerequest');
        $app['monolog']->addDebug('lib:' . $imageRequest->arguments['lib']);


        ///////////////////////
        $app['monolog']->addDebug('entering dynimage connecting service');

        /** /
          $ids = $moduleLoaded->getServiceIds();

          foreach ($ids as $id => $value) {
          if (is_numeric($value)) {

          $service = $moduleLoaded->get($value);

          if ($value != 'service_container' && $value != 'dynimage') {

          $app['monolog']->addDebug(get_class($service) .' connecting');
          $service->connect($request, $app);
          }
          }
          }
          /* */
        /**/
        $addonbag = $moduleLoaded->get('addonbag');
        $addonbag->connect($request, $app);
        /**/

        if (is_null($imageRequest->arguments)) {

            $app->abort(404);
        }

        if (isset($imageRequest->arguments['enabled']) && !$imageRequest->arguments['enabled']) {

            $app->abort(404);
        }


        if (isset($imageRequest->arguments['image'])) {

            $imageRequest->imagefilename = $imageRequest->arguments['image'];
        }

        if (!isset($imageRequest->imagefilename)) {
            $imageRequest->imagefilename = '';
        }

        ///////////////////////////


        $app['monolog']->addDebug('dispatch ' . Events::BEFORE_CREATE_IMAGE);
        $app['dispatcher']->dispatch(Events::BEFORE_CREATE_IMAGE);



        $app['monolog']->addDebug('entering dynimage create image');
        if (!file_exists($imageRequest->imagefilename)) {
            if (!isset($imageRequest->arguments['default'])) {
                $app->abort(404, 'The image was not found.');
            }

            $imageRequest->imagefilename = $imageRequest->arguments['default'];
        }
        $class = sprintf('\Imagine\%s\Imagine', $imageRequest->arguments['lib']);
        $app['monolog']->addDebug('imagefilename : ' . $imageRequest->imagefilename);
        $imageRequest->imagine = new $class();

        $imageRequest->image = $imageRequest->imagine->load($imageRequest->imagine->open($imageRequest->imagefilename));
        $app['monolog']->addDebug('dispatch ' . Events::AFTER_CREATE_IMAGE);
        $app['dispatcher']->dispatch(Events::AFTER_CREATE_IMAGE);
    }

    static public function terminate(Request $request, Response $response, Application $app) {

        $app['monolog']->addDebug('entering dynimage terminate');
        //$imageRequest = $app['dynimage.module']->get('imagerequest');
        $imageRequest = $app['dynimage.image'];

        if (!isset($imageRequest->image)) {

            $app->abort(404);
        }

        if (isset($imageRequest->arguments['format'])) {
            $format = $imageRequest->arguments['format'];
        } else {
            $format = pathinfo($imageRequest->imagefilename, PATHINFO_EXTENSION);
        }

        $response->headers->set('Content-Type', 'image/' . $format);
        $response->setContent($imageRequest->image->get($format));

        $response->setPublic();
        //$response->setStatusCode(200);
        if (isset($imageRequest->arguments['time-to-live'])) {

            $response->setTtl($imageRequest->arguments['time-to-live']);
        }

        return $response;
    }

}
