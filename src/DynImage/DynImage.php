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
               
            }

            $moduleFilename = $packages[$package]['modules'][$moduleName];

            $this->module = ModuleLoader::loadFromFile($moduleFilename, $package, $this->cache_dir, $this->env, $this->debug);
        }

 
     
        $imageManager = $this->module->get('image_manager');
     
        if (is_null($imageManager->arguments)) {
            throw new \InvalidArgumentException();
        }

        if (isset($imageManager->arguments['enabled']) && !$imageManager->arguments['enabled']) {
            throw new NotFoundHttpException();
        }

        if (!is_null($imagefilename)) {
            $imageManager->imagefilename = $imageManager->arguments['images_dir'].$imagefilename;
        }

        if (isset($imageManager->arguments['image'])) {

            $imageManager->imagefilename = $imageManager->arguments['image'];
        }
        

        if (!isset($imageManager->imagefilename)) {
            $imageManager->imagefilename = '';
        }
        
        $this->module->get('filter_manager');
        $this->module->get('dispatcher')->dispatch(Events::BEFORE_CREATE_IMAGE);
        
        
        if (!file_exists($imageManager->imagefilename)) {
            if (!isset($imageManager->arguments['default'])) {
               
                throw new NotFoundHttpException();
            }

            $imageManager->imagefilename = $imageManager->arguments['default'];
        }
        $class = sprintf('\Imagine\%s\Imagine', $imageManager->arguments['lib']);
        
        $imageManager->imagine = new $class();

        $imageManager->image = $imageManager->imagine->load($imageManager->imagine->open($imageManager->imagefilename));

        $this->module->get('dispatcher')->dispatch(Events::AFTER_CREATE_IMAGE);
        
        
        return $imageManager;
    }




}
