<?php
/* author: Ponomarev Denis <ponomarev@gmail.com> */

namespace dface\container;

use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

abstract class BaseContainer implements \ArrayAccess, ContainerInterface
{

	/**
	 * @param mixed $name
	 * @return bool|mixed
	 */
	public function offsetExists($name)
	{
		try{
			[$container, $item_name] = self::getDeepestContainerAndItemName($this, $name);
		}catch (NotFoundExceptionInterface $e){
			return false;
		}
		if (!$container instanceof ContainerInterface) {
			return false;
		}
		return $container->has($item_name);
	}

	/**
	 * @param mixed $name
	 * @return mixed
	 * @throws ContainerException
	 */
	public function offsetGet($name)
	{
		try{
			[$container, $item_name] = self::getDeepestContainerAndItemName($this, $name);
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

	protected static function getDeepestContainerAndItemName(ContainerInterface $container, string $name) : array
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

	public function offsetSet($offset, $value) : void
	{
		throw new \RuntimeException('Unsupported container access');
	}

	public function offsetUnset($offset) : void
	{
		throw new \RuntimeException('Unsupported container access');
	}

	/**
	 * @param $id
	 * @return mixed
	 * @throws ContainerException
	 */
	public function __invoke($id)
	{
		return $this->offsetGet($id);
	}

	/**
	 * @param $name
	 * @return mixed
	 * @throws ContainerException
	 */
	public function getItem($name)
	{
		return $this->offsetGet($name); // legacy
	}

	public function hasItem($name) : bool
	{
		return $this->offsetExists($name); // legacy
	}

}
