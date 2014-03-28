<?php

namespace DynImage;


use DynImage\Extension;
use DynImage\CompilerPass;
use Symfony\Component\Config\ConfigCache;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;

/**
 *  Charge un module et dump dans le cache
 * 
 */
class ModuleLoader {

   
    
    static private function load($cachedfilename,$moduleFilename,$className,$packagesDir,$debug) {
    
       
        $config = new ConfigCache($cachedfilename . '.php', $debug);
 
        if (!$config->isFresh()) {
            $container = new ContainerBuilder();

            if (!file_exists($moduleFilename)) {
                throw new \InvalidArgumentException(
                sprintf("The module file '%s' does not exist.", $moduleFilename));
            }

            $loader = new YamlFileLoader($container, new FileLocator($packagesDir));

            $loader->load(basename($moduleFilename));
            /**/
            //permet d'ajouter imageRequest en tant que service dans le module
            $extension = new Extension();
            $container->registerExtension($extension);
            $container->loadFromExtension($extension->getAlias());
            $container->addCompilerPass(new CompilerPass, PassConfig::TYPE_BEFORE_OPTIMIZATION);
            /**/

            $container->compile();

            $dumper = new PhpDumper($container);

            $config->write($dumper->dump(array('class' => $className)), $container->getResources());
        }
    
        require $cachedfilename.'.php';
        return new $className;
    }
    
    static public function loadFromFile($moduleFilename,$package,$cache_dir,$env,$debug) {

        $moduleFilename = $moduleFilename.'.'.$env.'.yml';
        $module = pathinfo($moduleFilename, PATHINFO_FILENAME); 
        $packagesDir = dirname(dirname($moduleFilename)).'/'.$package;
        $cachedfilename = $cache_dir.'/'.$package.$module;
        $className = str_replace('.','',$module);
        return self::load($cachedfilename,$moduleFilename,$className,$packagesDir,$debug);
    }
    
    public function loadFromDir($module,$packagesDir,$cache_dir,$env,$debug) {
        
        $moduleFilename = $packagesDir.'/'.$module.'.'.$env.'.yml';
        $cachedfilename = $cache_dir.'/'.basename($packagesDir).$module;
        $className = str_replace('.','',$module);
        return self::load($cachedfilename,$moduleFilename,$className,$packagesDir,$debug);
    }

}