<?php

namespace DynImage;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Description of DynImageCompilerPass
 *
 * @author pascal.roux
 */
class CompilerPass implements CompilerPassInterface {
    
    public function process (ContainerBuilder $container) {
        foreach($container->getDefinitions() as $id => $definition) {

            if(array_key_exists('imagerequest',$definition->getTags())) {
                //passe tous les parameters du container à l'instance du imagerequest
                $definition->setArguments(array($container->getParameterBag()->all()));
                
                
            }

        }
    }
}