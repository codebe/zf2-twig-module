<?php
return array(
    'view_manager' => array(
        'display_not_found_reason'  => true,
        'display_exceptions'        => true,
        'layout'                    => 'layout/layout',
        'doctype'                   => 'HTML5',
        'not_found_template'        => 'error/404',
        'exception_template'        => 'error/index',
        'strategies' => array(
            'zf-twig'   => 'ViewTwigRendererStrategy',
        )
    ),
    'zftwig' => array(
        'template_suffix'       => 'twig',
        'extensions'            => array(
            'ZfTwig' => 'ZfTwig\View\Extension'
        ),
        'environment_options'   => array(
            'cache' => 'data/cache/twig',
            'auto_reload' => true,
            'debug' => true,
            'autoescape' => false
        ),
    ),

);
