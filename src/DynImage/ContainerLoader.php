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
class ContainerLoader {

    private $container_dir;
    private $cache_dir;
    private $filename;
    private $dir;

    public function __construct($container_dir, $cache_dir) {

        $this->container_dir = $container_dir;
        $this->cache_dir = $cache_dir;
    }

    public function getConfigCache($filename, $debug) {
        $filename = pathinfo($filename, PATHINFO_FILENAME);
        return new ConfigCache($this->cache_dir . '/' . $filename . '.php', $debug);
    }

    public function load($dir, $filename, Application $app) {

        if (isset($app['dynimage.container'])) {

            return;
        }

        $this->dir = $dir;
        $this->cachedfilename = $this->dir . $filename . '.' . $app['env'] . '.yml';
        $this->filename = $filename . '.' . $app['env'] . '.yml';

        $config = $this->getConfigCache($this->dir . '/' . $this->cachedfilename, $app['debug']);

        $className = $filename . $app['env'];

        if (!$config->isFresh()) {
            $container = new ContainerBuilder();


            if (!file_exists($this->container_dir . '/' . $this->dir . '/' . $this->filename)) {
                throw new \InvalidArgumentException(
                sprintf("The container file '%s' does not exist.", $this->container_dir . '/' . $this->dir . '/' . $this->filename));
            }

            $loader = new YamlFileLoader($container, new FileLocator($this->container_dir . '/' . $this->dir));

            $loader->load($this->filename);
            /**/
            //permet d'ajouter imageRequest en tant que service dans le container
            $extension = new Extension();
            $container->registerExtension($extension);
            $container->loadFromExtension($extension->getAlias());
            $container->addCompilerPass(new CompilerPass, PassConfig::TYPE_BEFORE_OPTIMIZATION);
            /**/

            $container->compile();


            $dumper = new PhpDumper($container);

            $config->write($dumper->dump(array('class' => $className)), $container->getResources());
        }
        $filename = pathinfo($this->cachedfilename, PATHINFO_FILENAME);
        require $this->cache_dir . '/' . $filename . '.php';
        return new $className;
    }

}