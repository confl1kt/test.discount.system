<?php
return array(
    'doctrine' => array(
        'auto_generate_proxy_classes' => false,
        'connection' => array(
            'orm_default' => array(
                'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
                'params' => array(
                    'host' => 'localhost',
                    'port' => '3306',
                    'user' => 'root',
                    'password' => '',
                    'dbname' => 'something',
                    'charset'  => 'utf8',
                ),
            ),
            'odm_default' => array(
                'server'    => 'localhost',
                'port'      => '27017',
                'user'      => null,
                'password'  => null,
                'dbname'    => null,
                'options'   => array()
            ),
        ),
        'entitymanager' => array(
            'orm_default' => array(
                'connection'    => 'orm_default',
                'configuration' => 'orm_default'
            ),
        ),
        'configuration' => array(
            'orm_default' => array(
                'generate_proxies'  => false,
                'proxy_dir'         => APPLICATION_PATH.'/../../data/DoctrineORMModule/Proxy',
            ),
        ),
    ),
    'application' => array(
        'cache' => array(
            'simple' => array(
                'adapter' => array(
                    'options' => array(
                        'ttl' => 60,
                        'servers' => array(
                            array('127.0.0.1', 11211)
                        )
                    ),
                )
            ),
        ),
    ),
    'mailer' => array(
        'queue' => array(
            'adapter'=>array(
                'options'=>array(
                    'connection'=>'localhost:6379',
                ),
            ),
        ),
    ),
    'log' => array(
        'writers' => array(
            'errorLog' => array(
                'options' => array(
                    'server' => '127.0.0.1',
                    'database' => 'something',
                ),
            ),
            'accessLog' => array(
                'options' => array(
                    'server' => '127.0.0.1',
                    'database' => 'something',
                ),
            ),
            'changeLog' => array(
                'options' => array(
                    'server' => '127.0.0.1',
                    'database' => 'something',
                ),
            ),
        ),
    ),
    'redis' => array(
        'predis' => array(
            'connections' => array(
                'servers' => 'localhost:6379',
            )
        ),
    ),
    'authentication' => array(
        'save_handler_configs' => array(
            'predis' => array(
                'options' => array(
                    'prefix' => 'something_user_session_test:'
                )
            ),
        )
    ),
);