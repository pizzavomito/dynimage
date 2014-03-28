<?php

namespace DynImage;

use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;



/**
 * Description of DynImageExtension
 *
 * @author pascal.roux
 */
class Extension implements ExtensionInterface {

    public function load(array $configs, ContainerBuilder $container) {

        $loader = new YamlFileLoader(
                        $container,
                        new FileLocator(__DIR__)
        );
        $loader->load('dynimage.yml');
       
    }

    public function getAlias() {
        return 'ImageManager';
    }

    public function getXsdValidationBasePath() {
        return false;
    }

    public function getNamespace() {
        return false;
    }

}
