<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overridding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 * http://stackoverflow.com/questions/18664074/getting-error-peer-authentication-failed-for-user-postgres-when-trying-to-ge
 */

return array(
    // 'db' => array(
    //     'driver' => 'Pdo',
    //     'dsn'            => 'mysql:dbname=zf2tutorial;hostname=localhost',
    //     'username'       => 'root',
    //     'password'       => 'password',
    //     'driver_options' => array(
    //         PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
    //     ),
    // ),
    'db' => array(
        'driver' => 'Pdo',
        'dsn'            => 'mysql:dbname=zf2tutorial;hostname=localhost',
        'username'       => 'root',
        'password'       => 'password',
        'driver_options' => array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
        ),
        'adapters' => array(

            'db1' => array(
               'driver'         => 'Pdo',
               'dsn'             => 'mysql:dbname=zf2tutorial;host=localhost',
               'driver_options'  => array(
                    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
                ),
            ),

            'db2' => array(
               'driver'     => 'Pdo_pgsql',
               'dsn'        =>  'pgsql:host=127.0.0.1;dbname=zftutorial',
               'username'   =>  'postgres',
               'password'   =>'postgres',
            //    'driver_options'  => array(
            //         PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
            //     ),
            ),
        ),
    ),
    'doctrine' => array(
        'connection' => array(
            // default connection name
            'orm_default' => array(
                'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
                'params' => array(
                    'host'     => 'localhost',
                    'port'     => '3306',
                    'user'     => 'root',
                    'password' => 'password',
                    'dbname'   => 'zf2tutorial',
                )
            )
        )
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Db\Adapter\AdapterAbstractServiceFactory',
        ),
        'factories' => array(
            'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory',
        )
    ),
    // 'service_manager' => array(
    //     'factories' => array(
    //         'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory',
    //     ),
    // ),
);
