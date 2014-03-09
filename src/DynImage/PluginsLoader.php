<?php

namespace DynImage;

use Symfony\Component\Config\ConfigCache;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;
use Symfony\Component\Config\FileLocator;

/**
 * Charge les plugins et dump dans le cache
 */
class PluginsLoader {

    private $plugin_dir;
    private $cache_dir;
    private $filename;

    public function __construct($plugin_dir, $cache_dir) {

        $this->plugin_dir = $plugin_dir;
        $this->cache_dir = $cache_dir;
    }

    public function getConfigCache($filename, $debug) {
        $filename = pathinfo($filename, PATHINFO_FILENAME);
        return new ConfigCache($this->cache_dir . '/' . $filename . '.php', $debug);
    }

    public function load($filename, $debug = false) {

        if (!file_exists($this->plugin_dir . '/' . $filename)) {
            return;
        }
        $this->filename = $filename;

        $config = $this->getConfigCache($this->filename, $debug);

        $file = pathinfo(pathinfo($this->filename, PATHINFO_FILENAME), PATHINFO_FILENAME);

        $className = $file;

        if (!$config->isFresh()) {
            $container = new ContainerBuilder();

            if (!file_exists($this->plugin_dir . $this->filename)) {
                throw new \InvalidArgumentException(
                sprintf("The plugin file '%s' does not exist.", $this->plugin_dir . '/' . $this->filename));
            }

            $loader = new YamlFileLoader($container, new FileLocator($this->plugin_dir));

            $loader->load($this->filename);


            $container->compile();

            $dumper = new PhpDumper($container);

            $config->write($dumper->dump(array('class' => $className)), $container->getResources());
        }

        require $this->cache_dir . '/' . $file . '.php';

        return new $className;
    }

}