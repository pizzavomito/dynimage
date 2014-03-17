<?php

namespace DynImage;


use Symfony\Component\Config\ConfigCache;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;
use Symfony\Component\Config\FileLocator;


/**
 *  Load and dump config file in the cache directory
 * 
 */
class ConfigLoader {

    static public function load($file,$cache_dir, $debug) {

        $filename = pathinfo($file, PATHINFO_FILENAME); 
        $config =  new ConfigCache($cache_dir . '/' . $filename . '.php', $debug);

        $className = str_replace('.','',$filename);

        if (!$config->isFresh()) {
            $container = new ContainerBuilder();

            if (!file_exists($file)) {
                throw new \InvalidArgumentException(
                sprintf("The packager file '%s' does not exist.", $file));
            }

            $loader = new YamlFileLoader($container, new FileLocator(dirname($file)));

            $loader->load($file);
      
            $container->compile();

            $dumper = new PhpDumper($container);

            $config->write($dumper->dump(array('class' => $className)), $container->getResources());
        }
        
        require $cache_dir . '/' . $filename . '.php';
        return new $className;
    }

}