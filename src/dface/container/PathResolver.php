<?php

namespace dface\container;

use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class PathResolver implements ContainerInterface
{

	/** @var ContainerInterface */
	private $container;

	/**
	 * @param ContainerInterface $container
	 */
	public function __construct(ContainerInterface $container)
	{
		$this->container = $container;
	}

	public function get($name)
	{
		try{
			[$container, $item_name] = $this->getDeepestContainerAndItemName($name);
			if (!$container instanceof ContainerInterface) {
				$type = \gettype($container);
				$relative_name = \substr($name, 0, -\strlen($item_name));
				throw new ContainerException("'$relative_name' expected to be a ContainerInterface, got '$type'");
			}
			return $container->get($item_name);
		}catch (NotFoundExceptionInterface $e){
			throw new NotFoundException("'$name' not found", 0, $e);
		}
	}

	public function has($name) : bool
	{
		try{
			[$container, $item_name] = $this->getDeepestContainerAndItemName($name);
		}catch (NotFoundExceptionInterface $e){
			return false;
		}
		if (!$container instanceof ContainerInterface) {
			return false;
		}
		return $container->has($item_name);
	}

	/**
	 * @param string $name
	 * @return array
	 */
	private function getDeepestContainerAndItemName(string $name) : array
	{
		$path_arr = \explode('/', $name);
		$item_index = \count($path_arr) - 1;
		$item_name = $path_arr[$item_index];
		$container = $this->container;
		for ($i = 0; $i < $item_index; $i++) {
			$container_name = $path_arr[$i];
			/** @var $container ContainerInterface */
			$container = $container->get($container_name);
		}
		return [$container, $item_name];
	}
}