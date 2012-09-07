<?php

namespace ZfTwig\View;

use Zend\View\HelperPluginManager,
	Zend\ServiceManager\ServiceLocatorAwareInterface,
	Zend\ServiceManager\ServiceLocatorInterface,
	Twig_Environment,
	Twig_Function_Function as TwigFunction,
	ZfTwig\View\HelperFunction,
	ZfTwig\View\Resolver;

class Environment extends Twig_Environment {
	
	protected $_locator;
	protected $_plugin;
	
	public function __construct(Resolver $loader = null, HelperPluginManager $plugin, $options = array()) {
		parent::__construct($loader, $options);
		$this->setPlugin($plugin);
	}
	
	public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
	{
		$this->_locator = $serviceLocator;
		return $this;
	}
	
	public function getServiceLocator()
	{
		return $this->_locator;
	}
		
	public function getFunction($name) {
		//try to get the function from the environment itself
		$function = parent::getFunction($name);
		if (false !== $function){
			return $function;
		}
		
		//if not found, try to get it from  the broker and define it in the environment for later usage
		try{
			$helper = $this->plugin($name,array());
			if (null !== $helper){
				$function = new HelperFunction($name, array('is_safe' => array('html')));
				$this->addFunction($name, $function);
				return $function;
			}
		}catch(\Exception $exception){
			// ignore the exception and try to use a defined PHP function
		}
		
		// return any PHP function or any of the defined valid PHP constructs
		$constructs = array('isset', 'empty');
		//        if( strpos($name, '_') == 0 ){
		//            $_name = substr($name, 1);
		$_name = $name;
		if ( function_exists($_name) || in_array($_name, $constructs) ) {
			$function = new TwigFunction($_name);
			$this->addFunction($name, $function);
			return $function;
		}
		//        }
		
		// no function found
		return false;		
	}
	
	public function getPlugin() {
		if (null === $this->_plugin) {
			$this->_plugin = $this->getServiceLocator()->get('Zend\View\HelperPluginManager');
		}
		return $this->_plugin;
	}
	
	public function setPlugin($plugin) {
		$this->_plugin = $plugin;
		return $this;
	}
	
	/**
	 * Get plugin instance
	 *
	 * @param  string     $plugin  Name of plugin to return
	 * @param  null|array $options Options to pass to plugin constructor (if not already instantiated)
	 * @return mixed
	 */
	public function plugin($plugin, array $options = null)
	{
		$helper = $this->_plugin->get($plugin, $options);
		return $helper;
	}
}