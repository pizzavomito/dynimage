<?php

namespace DynImage;

use Silex\Application;
use DynImage\Extension;
use DynImage\CompilerPass;
use Symfony\Component\Config\ConfigCache;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;

/**
 *  Charge un container et dump dans le cache
 * 
 */
class ModuleLoader {

   
    private $cache_dir;
    private $moduleFilename;
    private $module;
    private $package;
    private $packagesDir;
    private $env;
    private $debug;

    public function __construct($cache_dir,$env,$debug) {

        $this->cache_dir = $cache_dir;
        $this->env = $env;
        $this->debug = $debug;
        
    }

    
    static private function load($cachedfilename,$moduleFilename,$className,$packagesDir,$debug) {
    
        //$cachedFilename = pathinfo($this->cachedfilename, PATHINFO_FILENAME);
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
        //$filename = pathinfo($cachedfilename, PATHINFO_FILENAME);
        //require $this->cache_dir . '/' . $filename . '.php';
        require $cachedfilename.'.php';
        return new $className;
    }
    
    static public function loadFromFile($moduleFilename,$package,$cache_dir,$env,$debug) {

        $moduleFilename = $moduleFilename.'.'.$env.'.yml';
        //$module = basename($moduleFilename);
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