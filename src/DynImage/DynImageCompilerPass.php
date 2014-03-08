<?php

namespace DynImage;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Description of DynImageCompilerPass
 *
 * @author pascal.roux
 */
class DynImageCompilerPass implements CompilerPassInterface {
    
    public function process (ContainerBuilder $container) {
        foreach($container->getDefinitions() as $id => $definition) {
            /** /
            if(array_key_exists('plugin',$definition->getTags())) {
                continue;
            }
            
            if(array_key_exists('filter',$definition->getTags())) {
                continue;
            }
            /**/
            if(array_key_exists('dynimage',$definition->getTags())) {
                //passe tous les parameters du container à l'instance du dynimage
                $definition->setArguments(array($container->getParameterBag()->all()));
                
                
            }

        }
    }
}