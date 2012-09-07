<?php

namespace ZfTwig\View\Service;

use Zend\ServiceManager\FactoryInterface,
	Zend\ServiceManager\ServiceLocatorInterface,
	ZfTwig\View\Strategy\TwigRendererStrategy;

class ViewTwigStrategyFactory implements FactoryInterface {
	
	/**
	 * Create and return the twig view strategy
	 *
	 * @param  ServiceLocatorInterface $serviceLocator
	 * @return TwigRendererStrategy
	 */
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		$twigRenderer = $serviceLocator->get('ViewTwigRenderer');
		$twigStrategy = new TwigRendererStrategy($twigRenderer);
		return $twigStrategy;
	}
	
}
