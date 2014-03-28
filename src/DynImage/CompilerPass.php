<?php

namespace DynImage;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Description of DynImageCompilerPass
 *
 * @author pascal.roux
 */
class CompilerPass implements CompilerPassInterface {

    public function process(ContainerBuilder $container) {

        foreach ($container->getDefinitions() as $id => $definition) {

            if (array_key_exists('dynimage.image_manager', $definition->getTags())) {
                //passe tous les parameters du container à l'instance du imagerequest
                $definition->setArguments(array($container->getParameterBag()->all()));
                continue;
            }

            if (array_key_exists('dynimage.filter', $definition->getTags())) {
              
                
                $dispatcher = new Reference('dispatcher');

                $imageManager = new Reference('image_manager');

                $definition->addMethodCall('connect', array($imageManager, $dispatcher));
            }
            
            
        }
    }

}
