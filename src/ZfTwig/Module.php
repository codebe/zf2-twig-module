<?php

namespace ZfTwig;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface,
	Zend\Mvc\MvcEvent;

class Module implements AutoloaderProviderInterface {
	
	/**
	 * @var \Zend\ServiceManager\ServiceManager
	 */
	protected static $serviceManager;
	
	public function onBootstrap(MvcEvent $event)
	{
		// Set the static service manager instance so we can use it everywhere in the module
		$app = $event->getApplication();
		static::$serviceManager = $app->getServiceManager();
	}
		
	/**
	 * Get Autoloader Config
	 * @return array
	 */
	public function getAutoloaderConfig()
	{
		return array(
				'Zend\Loader\ClassMapAutoloader' => array(
						__DIR__ . '/autoload_classmap.php',
				)
		);
	}

	/**
	 * Get Service Configuration
	 * @return array
	 */
	public function getServiceConfiguration(){
		return include __DIR__ . '/config/service.config.php';
	}
	
	/**
	 * Get Module Configuration
	 * @return mixed
	 */
	public function getConfig()
	{
		$config = include __DIR__ . '/config/module.config.php';
		return $config;
	}
	
	/**
	 * Return the ServiceManager instance
	 * @static
	 * @return \Zend\ServiceManager\ServiceManager
	 */
	public static function getServiceManager()
	{
		return static::$serviceManager;
	}	
}