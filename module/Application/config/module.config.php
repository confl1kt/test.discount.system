<?php
return array(
    'doctrine' => array(
        'driver' => array(
            'application_entities' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(
                    __DIR__ . '/../src/Application/Entity',
                )
            ),
            'orm_default' => array(
                'drivers' => array(
                    'Application\Entity' => 'application_entities'
                )
            )
        ),
        'configuration' => array(
            'orm_default' => array(
                'metadata_cache' => 'my_memcache',
                'query_cache' => 'my_memcache',
                'result_cache' => 'my_memcache',
                'datetime_functions' => array(
                    'month' => 'Application\DoctrineExtensions\Query\Mysql\Month',
                    'year' => 'Application\DoctrineExtensions\Query\Mysql\Year',
                    'date' => 'Application\DoctrineExtensions\Query\Mysql\Date',
                    'binary' => 'Application\DoctrineExtensions\Query\Mysql\Binary',
                    'day' => 'Application\DoctrineExtensions\Query\Mysql\Day'
                ),
            ),
            'odm_default' => array(
                'metadata_cache' => 'my_memcache',
                'driver' => 'odm_default',
                'generate_proxies' => false,
                'proxy_dir' => 'data/DoctrineMongoODMModule/Proxy',
                'proxy_namespace' => 'DoctrineMongoODMModule\Proxy',
                'generate_hydrators' => false,
                'hydrator_dir' => 'data/DoctrineMongoODMModule/Hydrator',
                'hydrator_namespace' => 'DoctrineMongoODMModule\Hydrator',
                'filters' => array(),
                'default_db' => 'something'
            ),
        )
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_map' => include __DIR__ . '/../template_map.php',
        'template_path_stack' => array(
            'application' => __DIR__ . '/../view',
        ),
        'strategies' => array(
            'ViewJsonStrategy',
            'ViewFeedStrategy'
        ),
    ),
    'application' => array(
        'cache' => array(
            'simple' => array(
                'adapter' => array(
                    'name' => 'memcached',
                    'options' => array(
                        'ttl' => 60,
                        'namespace' => 'something_simple_cache',
                        'key_pattern' => null,
                        'readable' => true,
                        'writable' => true,
                    ),
                    'plugins' => array('serializer'),
                )
            ),
        ),
    ),

    'session' => array(
        'config' => array(
            'class' => 'Zend\Session\Config\SessionConfig',
            'options' => array(
                'name' => 'myapp',
            ),
        ),
        'storage' => 'Zend\Session\Storage\SessionArrayStorage',
        'validators' => array(
            array(
                'Zend\Session\Validator\RemoteAddr',
                'Zend\Session\Validator\HttpUserAgent',
            ),
        ),
    ),
);