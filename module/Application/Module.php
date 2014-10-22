<?php
namespace Application;

use Doctrine\Common\Cache\MemcacheCache;
use HttpRequest;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ControllerPluginProviderInterface;
use Zend\ModuleManager\Feature\FormElementProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\ModuleManager\Feature\ViewHelperProviderInterface;
use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\ServiceManager;
use Zend\Session\Container;
use Zend\Session\SessionManager;
use Zend\Stdlib\ArrayUtils;

class Module implements ConfigProviderInterface,
    AutoloaderProviderInterface,
    ServiceProviderInterface,
    ViewHelperProviderInterface,
    FormElementProviderInterface,
    ControllerPluginProviderInterface
{

    public function onBootstrap(MvcEvent $e)
    {
        $application = $e->getApplication();
        $sm = $application->getServiceManager();
        $em = $sm->get('Doctrine\ORM\EntityManager');
        $platform = $em->getConnection()->getDatabasePlatform();
        $platform->registerDoctrineTypeMapping('enum', 'string');

        $eventManager = $application->getEventManager();

        $request = $e->getRequest();
        if (!$request instanceof HttpRequest) {
            return;
        }


        //TODO unquote to move sessions to redis
        $this->bootstrapSession($e);
    }

    public function bootstrapSession($e)
    {
        $session = $e->getApplication()
            ->getServiceManager()
            ->get('Zend\Session\SessionManager');

        $session->start();

        $container = new Container('initialized');
        if (!isset($container->init)) {
            $session->regenerateId(true);
            $container->init = 1;
        }
    }


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
                __DIR__ . '/configs/module.config.php',
                //__DIR__ . '/configs/module.config.navigation.php',
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

    /**
     * Expected to return \Zend\ServiceManager\Config object or array to
     * seed such an object.
     *
     * @return array|\Zend\ServiceManager\Config
     */
    public function getFormElementConfig()
    {
        return array(
            'initializers' => [
                'EntityManager' => 'Application\Service\Initializer\EntityManagerInitializer',
            ],
        );
    }

    /**
     * Expected to return \Zend\ServiceManager\Config object or array to
     * seed such an object.
     *
     * @return array|\Zend\ServiceManager\Config
     */
    public function getServiceConfig()
    {
        return array(
            'initializers' => [
                'EntityManager' => 'Application\Service\Initializer\EntityManagerInitializer',
            ],
            'aliases' => [
                'Cache' => 'Zend\Cache\StorageFactory',
                'translator' => 'MvcTranslator',
                'ODMManager' => 'doctrine.documentmanager.odm_default',
            ],
            'factories' => [
                'Zend\Session\SessionManager' => function (ServiceManager $sm) {
                        $config = $sm->get('Config')['authentication'];

                        $sessionConfig = $sessionStorage = $sessionSaveHandler = $validators = null;

                        if (isset($config['session_manager'])) {
                            $config = $config['session_manager'];

                            if (isset($config['config'])) {
                                $class = isset($session['config']['class']) ? $session['config']['class'] : 'Zend\Session\Config\SessionConfig';
                                $options = isset($session['config']['options']) ? $session['config']['options'] : [];
                                $sessionConfig = new $class();
                                $sessionConfig->setOptions($options);
                            }

                            if (isset($config['storage'])) {
                                $class = $config['storage'];
                                $sessionStorage = $sm->has($class) ? $sm->get($class) : new $class();
                            }

                            if (isset($config['save_handler'])) {
                                $sessionSaveHandler = $sm->get($config['save_handler']);
                            }

                            if (isset($config['validators'])) {
                                $validators = $config['validators'];
                            }
                        }

                        $sessionManager = new SessionManager($sessionConfig, $sessionStorage, $sessionSaveHandler);

                        if ($validators) {
                            $chain = $sessionManager->getValidatorChain();
                            $function = function ($validator) use ($chain) {
                                $validator = new $validator;
                                $chain->attach('session.validate', [$validator, 'isValid']);
                            };
                            array_map($function, $validators);
                        }

                        Container::setDefaultManager($sessionManager);
                        return $sessionManager;
                    },
                'Zend\Cache\StorageFactory' => function (ServiceManager $sm) {
                        $options = $sm->get('Config');
                        return \Zend\Cache\StorageFactory::factory($options['application']['cache']['simple']);
                    },
                'doctrine.cache.my_memcache' => function (ServiceManager $sm) {
                        $cache = new MemcacheCache();
                        $memcache = new \Memcache();
                        $memcache->connect('localhost', 11211);
                        $cache->setMemcache($memcache);
                        $cache->setNamespace('something_');
                        return $cache;
                    },
                'Zend\Cache\SessionStorageFactory' => function (ServiceManager $sm) {
                        $options = $sm->get('Config');
                        return \Zend\Cache\StorageFactory::factory(
                            $options['authentication']['save_handler_configs']['cache']
                        );
                    },
                'Zend\Session\SaveHandler\Cache' => function (ServiceManager $sm) {
                        return new \Zend\Session\SaveHandler\Cache($sm->get('Zend\Cache\SessionStorageFactory'));
                    },
            ]
        );
    }

    /**
     * Expected to return \Zend\ServiceManager\Config object or array to
     * seed such an object.
     *
     * @return array|\Zend\ServiceManager\Config
     */
    public function getViewHelperConfig()
    {
        return array(
            'invokables' => [
                'flashMessenger' => 'Application\View\Helper\FlashMessenger',
                'formRow' => 'Application\View\Helper\FormRow',
                'formErrorList' => 'Application\View\Helper\FormErrorList'
            ],
        );
    }
}