<?php
return array(
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
);