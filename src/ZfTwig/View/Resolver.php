<?php

namespace ZfTwig\View;

use Zend\View\Resolver\AggregateResolver,
    Twig_LoaderInterface as LoaderInterface;

class Resolver extends AggregateResolver implements LoaderInterface {
	/**
	 * Gets the source code of a template, given its name.
	 *
	 * @param  string $name The name of the template to load
	 * @return string The template source code
	 */
	public function getSource($name)
	{
		$path = $this->resolve($name);
		if (!$path){
			throw new Exception\TemplateException(sprintf('Template "%s" not found.', $name));
		}
		return file_get_contents($path);
	}
	
	/**
	 * Gets the cache key to use for the cache for a given template name.
	 *
	 * @param  string $name The name of the template to load
	 * @return string The cache key
	 */
	public function getCacheKey($name)
	{
		$path = $this->resolve($name);
		return $path;
	}
	
	/**
	 * Returns true if the template is still fresh.
	 *
	 * @param string    $name The template name
	 * @param timestamp $time The last modification time of the cached template
	 * @return boolean
	 */
	public function isFresh($name, $time)
	{
		$path = $this->resolve($name);
		if (!$path){
			return false;
		}
		return filemtime($path) < $time;
	}	
} 