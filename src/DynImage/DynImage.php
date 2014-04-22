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
    private $debug;
    public $module;
    public $imageManager;

    public function __construct($cache_dir, $debug) {
        $this->cache_dir = $cache_dir;
        $this->debug = $debug;
    }

    public function getModule() {
        return $this->module;
    }

    public function load($moduleFilename) {

        $this->module = ModuleLoader::load($moduleFilename, $this->cache_dir, $this->debug);

        $this->imageManager = $this->module->get('image_manager');


        return $this->module;
    }

    public function createImage($string, $imagefilename) {

        $this->imageManager->imagefilename = $imagefilename;


        if ($this->module->hasParameter('enabled') && !$this->module->getParameter('enabled')) {
            throw new NotFoundHttpException();
        }


        $this->module->get('transformer');
        $this->module->get('dispatcher')->dispatch(Events::BEFORE_CREATE_IMAGE);

        $class = sprintf('\Imagine\%s\Imagine', $this->module->getParameter('lib'));

        $this->imageManager->imagine = new $class();

        $this->imageManager->image = $this->imageManager->imagine->load($string);

        $this->module->get('dispatcher')->dispatch(Events::AFTER_CREATE_IMAGE);

        $this->module->get('dispatcher')->dispatch(Events::BREAKFAST_APPLY_FILTER);

        $this->module->get('dispatcher')->dispatch(Events::LUNCH_APPLY_FILTER);

        $this->module->get('dispatcher')->dispatch(Events::DINNER_APPLY_FILTER);

        $this->module->get('dispatcher')->dispatch(Events::FINISH_CREATE_IMAGE);



        return $this->imageManager->image;
    }

}
