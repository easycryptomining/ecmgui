<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 * 
 * https://github.com/olegkrivtsov/using-zf3-book-samples
 * https://www.phpfacile.com/apprendre_le_php/base_de_donnees_avec_zend_framework
 * https://github.com/unclexo/zf3-datatables-crud
 */

use Zend\Session\Storage\SessionArrayStorage;
use Zend\Session\Validator\RemoteAddr;
use Zend\Session\Validator\HttpUserAgent;

return [
     'db' => [
        'driver' => 'Pdo',
        'dsn'    => sprintf('sqlite:%s/data/ecmgui.db', realpath(getcwd())),
    ],
    // Session configuration.
    'session_config' => [
        'cookie_lifetime' => 60*60*1,  // Session cookie will expire in 1 hour.
        'gc_maxlifetime'  => 60*60*1,  // Store session data on server maximum for 1 hour.
    ],
    // Session manager configuration.
    'session_manager' => [
        // Session validators (used for security).
        'validators' => [
            RemoteAddr::class,
            HttpUserAgent::class,
        ]
    ],
    // Session storage configuration.
    'session_storage' => [
        'type' => SessionArrayStorage::class
    ],
    'service_manager' => [
        'factories' => [
            'Zend\Db\Adapter\Adapter' => 
            'Zend\Db\Adapter\AdapterServiceFactory',
        ],
    ],
];
