<?php
namespace Application;

use Zend\Session\Container;
use Zend\Stdlib\ArrayUtils;

class Module
{
    /**
     * Return an array for passing to Zend\Loader\AutoloaderFactory.
     *
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    /**
     * Returns configuration to merge with application configuration
     *
     * @return array|\Traversable
     */
    public function getConfig()
    {
        return array_reduce(
            [
                __DIR__ . '/config/module.config.php',
                //__DIR__ . '/config/module.config.navigation.php',
            ],
            function ($config, $configFile) {
                return ArrayUtils::merge($config, include $configFile);
            },
            []
        );
    }

    /**
     * Expected to return \Zend\ServiceManager\Config object or array to
     * seed such an object.
     *
     * @return array|\Zend\ServiceManager\Config
     */
    public function getControllerPluginConfig()
    {
        return array(

        );
    }
}