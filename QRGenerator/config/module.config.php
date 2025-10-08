<?php
declare(strict_types=1);

namespace QRCodes;

use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use Laminas\ServiceManager\Factory\InvokableFactory;

return [
    'controllers' => [
        'factories' => [
            'QRCodes\\Controller\\Admin\\QrController' => InvokableFactory::class,
        ],
        'aliases' => [
            // Allow using the short service name "QRCodes\\Controller\\Admin\\Qr"
            'QRCodes\\Controller\\Admin\\Qr' => 'QRCodes\\Controller\\Admin\\QrController',
        ],
    ],

    'router' => [
        'routes' => [
            'admin' => [
                'child_routes' => [
                    'qr-codes' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/qr-codes',
                            'defaults' => [
                                '__NAMESPACE__' => 'QRCodes\\Controller\\Admin',
                                'controller' => 'Qr',
                                'action' => 'browse',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'default' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/:action',
                                    'constraints' => [
                                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                    ],
                                    'defaults' => [
                                        'action' => 'browse',
                                    ],
                                ],
                            ],
                            'id' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/:id[/:action]',
                                    'constraints' => [
                                        'id' => '\\d+',
                                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                    ],
                                    'defaults' => [
                                        'action' => 'show',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    // No admin navigation entry; the module adds an item tab instead.

    'view_manager' => [
        'template_path_stack' => [
            dirname(__DIR__) . '/view',
        ],
    ],
];
