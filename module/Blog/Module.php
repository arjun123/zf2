<?php
/**
 * Module Bootstrap
 *
 * @package Blog
 */
namespace Blog;

/**
 * Module Bootstrap
 *
 * @package Blog
 */
class Module
{

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'db-adapter' => function($sm) {
                    return $sm->get('db');
                },
            ),
        );
    }
}