<?php

namespace dface\container;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

abstract class PathResolver
{

	/**
	 * @throws ContainerExceptionInterface
	 */
	public static function containerHasPath(ContainerInterface $container, string $name) : bool
	{
		try{
			[$container, $item_name] = self::getDeepestContainerAndItemName($container, $name);
		}catch (NotFoundExceptionInterface){
			return false;
		}
		if (!$container instanceof ContainerInterface) {
			return false;
		}
		return $container->has($item_name);
	}

	/**
	 * @param ContainerInterface $container
	 * @param mixed $name
	 * @return mixed
	 * @throws ContainerExceptionInterface
	 */
	public static function containerGetPath(ContainerInterface $container, string $name) : mixed
	{
		try{
			[$container, $item_name] = self::getDeepestContainerAndItemName($container, $name);
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

	/**
	 * @throws ContainerExceptionInterface
	 * @throws NotFoundExceptionInterface
	 */
	public static function getDeepestContainerAndItemName(ContainerInterface $container, string $name) : array
	{
		$path_arr = \explode('/', $name);
		$item_index = \count($path_arr) - 1;
		$item_name = $path_arr[$item_index];
		for ($i = 0; $i < $item_index; $i++) {
			$container_name = $path_arr[$i];
			/** @var $container ContainerInterface */
			$container = $container->get($container_name);
		}
		return [$container, $item_name];
	}


}
