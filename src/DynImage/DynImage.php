<?php

namespace DynImage;


use Symfony\Component\EventDispatcher\EventDispatcher;
use DynImage\Transformer;
/**
 * 
 *
 * @author pascal.roux
 */
class DynImage {

   


    static public function createImage(Transformer $transformer, $imageContent, $imagefilename, $lib='Gd', $parameters=array()) {

        $imageManager = new ImageManager();
        $imageManager->imagefilename = $imagefilename;
        
        $dispatcher = new EventDispatcher();
        
        $filters = $transformer->getFilters();
        
        if (!empty($filters)) {
            foreach ($filters as $filter) {
              
               $filter->connect($imageManager,$dispatcher,$parameters);
            }
        }
       
        $dispatcher->dispatch(Events::BEFORE_CREATE_IMAGE);

        $class = sprintf('\Imagine\%s\Imagine', $lib);

        $imageManager->imagine = new $class();

        $imageManager->image = $imageManager->imagine->load($imageContent);

        $dispatcher->dispatch(Events::AFTER_CREATE_IMAGE);

        $dispatcher->dispatch(Events::EARLY_APPLY_FILTER);

        $dispatcher->dispatch(Events::LATE_APPLY_FILTER);

        $dispatcher->dispatch(Events::FINISH_CREATE_IMAGE);



        return $imageManager;
    }
    
     static public function getImage(Transformer $transformer, $imageContent, $imagefilename, $lib='Gd',$parameters=array()) {
         $imageManager = self::createImage($transformer, $imageContent, $imagefilename, $lib, $parameters);
         
         return $imageManager->image;
     }
    

    /** /
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

        $this->module->get('dispatcher')->dispatch(Events::EARLY_APPLY_FILTER);

        $this->module->get('dispatcher')->dispatch(Events::LATE_APPLY_FILTER);

        $this->module->get('dispatcher')->dispatch(Events::FINISH_CREATE_IMAGE);



        return $this->imageManager->image;
    }
     /**/
     

}
