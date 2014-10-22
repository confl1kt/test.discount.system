<?php
return array(

    'view_manager' => array(
        'template_map' => include __DIR__ . '/../template_map.php',
        'template_path_stack' => array(
            'test' => __DIR__ . '/../view',
        ),
    ),
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/',
                    'defaults' => array(
                        'controller' => 'test',
                        'action' => 'index'
                    ),
                ),
            ),
        ),
    ),
);