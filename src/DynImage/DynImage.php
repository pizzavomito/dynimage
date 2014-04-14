<?php

namespace DynImage;

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
    private $packager;
    public $module;
    
    public $imageManager;
    
    
    public function __construct($cache_dir, $packager, $env, $debug) {
        $this->cache_dir = $cache_dir;
        $this->env = $env;
        $this->debug = $debug;
        $this->packager = $packager;
    }
    
    public function getModule() {
        return $this->module;
    }
    
    public function load($package, $moduleName) {
         if (is_dir($this->packager)) {
            $this->module = ModuleLoader::loadFromDir($moduleName, $this->packager . $package, $this->cache_dir, $this->env, $this->debug);
        } else {
            $packagerLoaded = PackagerLoader::load($this->packager . '.' . $this->env . '.yml', $this->cache_dir, $this->debug);

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


        $this->imageManager = $this->module->get('image_manager');
       
        
        return $this->module;
    }
    
    public function createImage($imagefilename) {
  
        $this->imageManager->imagefilename = $imagefilename;
     
 
        if ($this->module->hasParameter('enabled') && !$this->module->getParameter('enabled')) {
            throw new NotFoundHttpException();
        }
        
        
        $this->module->get('filter_manager');
        $this->module->get('dispatcher')->dispatch(Events::BEFORE_CREATE_IMAGE);
       
        $class = sprintf('\Imagine\%s\Imagine', $this->module->getParameter('lib'));
        
        $this->imageManager->imagine = new $class();

        $this->imageManager->image = $this->imageManager->imagine->load($this->imageManager->imagine->open($this->imageManager->imagefilename));

        $this->module->get('dispatcher')->dispatch(Events::AFTER_CREATE_IMAGE);
        
        $this->module->get('dispatcher')->dispatch(Events::BREAKFAST_APPLY_FILTER);
        
        $this->module->get('dispatcher')->dispatch(Events::LUNCH_APPLY_FILTER);
        
        $this->module->get('dispatcher')->dispatch(Events::DINNER_APPLY_FILTER);
        
         $this->module->get('dispatcher')->dispatch(Events::FINISH_CREATE_IMAGE);
        
        
        
        return $this->imageManager->image;
        
    }




}
