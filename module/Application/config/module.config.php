<?php

namespace Application;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
            'home' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action' => 'index',
                    ],
                ],
            ],
            'application' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/application[/:action]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]+',
                    ],
                    'defaults' => [
                        'controller'    => Controller\IndexController::class,
                        'action'        => 'index',
                    ],
                ],
            ],
            'settings' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/settings[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]+',
                        'id' => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\SettingsController::class,
                        'action' => 'index',
                    ],
                ],
            ], 
        ],
    ],
    'session_containers' => [
        'UserEcmgui'
    ],
    // The following registers our custom view 
    // helper classes in view plugin manager.
    'view_helpers' => [
        'factories' => [
            View\Helper\Menu::class => InvokableFactory::class,
            View\Helper\Breadcrumbs::class => InvokableFactory::class,
        ],
        'aliases' => [
            'mainMenu' => View\Helper\Menu::class,
            'pageBreadcrumbs' => View\Helper\Breadcrumbs::class,
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => Factory\Controller\IndexControllerFactory::class,
            Controller\SettingsController::class => Factory\Controller\SettingsControllerFactory::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
            Service\PluginsService::class => Factory\Service\PluginsServiceFactory::class,
            Mapper\PluginsMapper::class => Factory\Mapper\PluginsMapperFactory::class,
            Form\PluginsForm::class => Factory\Form\PluginsFormFactory::class,
            Service\WalletsService::class => Factory\Service\WalletsServiceFactory::class,
            Mapper\WalletsMapper::class => Factory\Mapper\WalletsMapperFactory::class,
            Service\SettingsService::class => Factory\Service\SettingsServiceFactory::class,
            Mapper\SettingsMapper::class => Factory\Mapper\SettingsMapperFactory::class,
            Form\SettingsForm::class => Factory\Form\SettingsFormFactory::class,
            Service\WorkersService::class => Factory\Service\WorkersServiceFactory::class,
            Mapper\WorkersMapper::class => Factory\Mapper\WorkersMapperFactory::class,
        ],
        'aliases' => [
            'PluginsService' => Service\PluginsService::class,
            'PluginsMapper' => Mapper\PluginsMapper::class,
            'PluginsForm' => Form\PluginsForm::class,
            'WalletsService' => Service\WalletsService::class,
            'WalletsMapper' => Mapper\WalletsMapper::class,
            'SettingsService' => Service\SettingsService::class,
            'SettingsMapper' => Mapper\SettingsMapper::class,
            'SettingsForm' => Form\SettingsForm::class,
            'WorkersService' => Service\WorkersService::class,
            'WorkersMapper' => Mapper\WorkersMapper::class,
        ],
        'shared' => [], 
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_map' => [
            'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404' => __DIR__ . '/../view/error/404.phtml',
            'error/index' => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
        'strategies' => [
            'ViewJsonStrategy',
        ],
    ],
    // The following key allows to define custom styling for FlashMessenger view helper.
    'view_helper_config' => [
        'flashmessenger' => [
            'message_open_format' => '<div%s>',
            'message_close_string' => '</div>',
            'message_separator_string' => '<p></p>'
        ]
    ],
];
