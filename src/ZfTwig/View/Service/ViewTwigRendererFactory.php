<?php

namespace ZfTwig\View\Service;

use Zend\ServiceManager\FactoryInterface,
	Zend\ServiceManager\ServiceLocatorInterface,
	ZfTwig\View\Strategy\TwigRendererStrategy,
	ZfTwig\View\Environment,
	ZfTwig\View\Renderer;

class ViewTwigRendererFactory implements FactoryInterface {
	
	/**
	 * Create and return the twig view renderer
	 *
	 * @param  ServiceLocatorInterface $serviceLocator
	 * @return TwigRendererStrategy
	 */
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		$config = $serviceLocator->get('Configuration');
		$config = isset($config['zftwig']) && (is_array($config['zftwig']) || $config['zftwig'] instanceof ArrayAccess)
		? $config['zftwig']
		: array();
	
		$viewLoader = $serviceLocator->get('view_manager')->getResolver();		
		$loader = new \ZfTwig\View\Resolver();
		foreach($viewLoader->getIterator() as $resolver){			
			if ($resolver instanceof \Zend\View\Resolver\TemplatePathStack){				
				$resolver = clone $resolver;
				$resolver->setDefaultSuffix($config['template_suffix']);
			}
			$loader->attach($resolver);
		}
		
		$helper = $serviceLocator->get('view_manager')->getHelperManager();
		$options = isset($config['environment_options']) ? $config['environment_options'] : array();
		$environment = new Environment($loader, $helper, $options);
		if (isset($config['extensions'])){
			foreach($config['extensions'] as $extension){
				$extensionInstance = new $extension();
				if ($extensionInstance instanceof \Twig_ExtensionInterface){
					$environment->addExtension($extensionInstance);
				}
			}
		}
	
		$twigRenderer = new Renderer($environment);
		$this->defaultRendererSetup($twigRenderer);
		return $twigRenderer;
	}	
	
	private function defaultRendererSetup($renderer)
	{
		$renderer->plugin('headTitle')
		->setSeparator(' - ')
		->setAutoEscape(false);
	}
	
}