<?php
// Define path to application directory
defined('APPLICATION_PATH')
|| define('APPLICATION_PATH', realpath('module/Application'));

// Define application environment
defined('APPLICATION_ENV')
|| define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// Define seo hook
defined('APPLICATION_SEO')
|| define('APPLICATION_SEO', (getenv('APPLICATION_SEO') ? (bool)getenv('APPLICATION_SEO') : false));

return array(
    // This should be an array of module namespaces used in the application.
    'modules' => array(
        'DoctrineModule',
        'DoctrineORMModule',
        'DoctrineMongoODMModule',
        'Application',
        'Redis',
        'Log',
        'I18n',
        'Content',
        'Service',
        'Redis',
        'Mailer'
    ),

    // These are various options for the listeners attached to the ModuleManager
    'module_listener_options' => array(
        // This should be an array of paths in which modules reside.
        // If a string key is provided, the listener will consider that a module
        // namespace, the value of that key the specific path to that module's
        // Module class.
        'module_paths' => array(
            './module',
            './vendor',
        ),

        // An array of paths from which to glob configuration files after
        // modules are loaded. These effectively overide configuration
        // provided by modules themselves. Paths may use GLOB_BRACE notation.
        'config_glob_paths' => array(
            'config/autoload/{,*.}{global,local}.php',
            'module/Application/configs/{,*.}{global,local}.php',
            'config/autoload/' . APPLICATION_ENV . '.config.php',
        ),

        // Whether or not to enable a configuration cache.
        // If enabled, the merged configuration will be cached and used in
        // subsequent requests.
        //'config_cache_enabled' => $booleanValue,
//        'config_cache_enabled' => APPLICATION_ENV !== 'testing',

        // The key used to create the configuration cache file name.
        //'config_cache_key' => $stringKey,
//        'config_cache_key' => APPLICATION_ENV,

        // Whether or not to enable a module class map cache.
        // If enabled, creates a module class map cache which will be used
        // by in future requests, to reduce the autoloading process.
        //'module_map_cache_enabled' => $booleanValue,
        'module_map_cache_enabled' => true,

        // The key used to create the class map cache file name.
        //'module_map_cache_key' => $stringKey,
        'module_map_cache_key' => APPLICATION_ENV,

        // The path in which to cache merged configuration.
        //'cache_dir' => $stringPath,
        'cache_dir' => 'data/cache',

        // Whether or not to enable modules dependency checking.
        // Enabled by default, prevents usage of modules that depend on other modules
        // that weren't loaded.
        // 'check_dependencies' => true,
//        'check_dependencies' => true
    ),

    // Used to create an own service manager. May contain one or more child arrays.
    //'service_listener_options' => array(
    //     array(
    //         'service_manager' => $stringServiceManagerName,
    //         'config_key'      => $stringConfigKey,
    //         'interface'       => $stringOptionalInterface,
    //         'method'          => $stringRequiredMethodName,
    //     ),
    // )

    // Initial configuration with which to seed the ServiceManager.
    // Should be compatible with Zend\ServiceManager\Config.
    // 'service_manager' => array(),
);