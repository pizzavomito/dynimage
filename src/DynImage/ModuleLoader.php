<?php

namespace DynImage;

use DynImage\Extension;
use DynImage\CompilerPass;
use Symfony\Component\Config\ConfigCache;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\Config\Loader\LoaderResolver;
use Symfony\Component\Config\Loader\DelegatingLoader;

/**
 *  Charge un module et dump dans le cache
 * 
 */
class ModuleLoader {

   
    static public function load($moduleFilename, $cache_dir, $debug) {


        //$moduleFilename .= '.' . $env . '.xml';
        $module = pathinfo($moduleFilename, PATHINFO_FILENAME);
        $cachedfilename = $cache_dir . '/' . $module;
        $className = str_replace('.', '', $module);

        $config = new ConfigCache($cachedfilename . '.php', $debug);

        if (!$config->isFresh()) {
            $container = new ContainerBuilder();

            if (!file_exists($moduleFilename)) {
                throw new \InvalidArgumentException(
                sprintf("The module file '%s' does not exist.", $moduleFilename));
            }

            $loader = new XmlFileLoader($container, new FileLocator(dirname($moduleFilename)));

            $loader->load(basename($moduleFilename));
            /**/
            //permet d'ajouter imageManager en tant que service dans le module
            $extension = new Extension();
            $container->registerExtension($extension);
            $container->loadFromExtension($extension->getAlias());
            $container->addCompilerPass(new CompilerPass, PassConfig::TYPE_BEFORE_OPTIMIZATION);
            /**/

            $container->compile();

            $dumper = new PhpDumper($container);

            $config->write($dumper->dump(array('class' => $className)), $container->getResources());
        }

        require $cachedfilename . '.php';
        return new $className;
    }


}
