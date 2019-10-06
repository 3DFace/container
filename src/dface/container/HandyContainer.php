<?php
/* author: Ponomarev Denis <ponomarev@gmail.com> */

namespace dface\container;

use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

abstract class HandyContainer implements \ArrayAccess, ContainerInterface
{

	/**
	 * @param mixed $name
	 * @return bool|mixed
	 */
	public function offsetExists($name)
	{
		return $this->has($name);
	}

	/**
	 * @param mixed $name
	 * @return mixed
	 */
	public function offsetGet($name)
	{
		return $this->get($name);
	}

	public function offsetSet($offset, $value) : void
	{
		throw new \RuntimeException('Unsupported container access');
	}

	public function offsetUnset($offset) : void
	{
		throw new \RuntimeException('Unsupported container access');
	}

	public function get($name)
	{
		$path_arr = \explode('/', $name);
		if(\count($path_arr) === 1){
			return $this->getItem($name);
		}
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
		$path_arr = \explode('/', $name);
		if(\count($path_arr) === 1){
			return $this->hasItem($name);
		}
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

	public function __invoke($id)
	{
		return $this->getItem($id);
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
		$container = $this;
		for ($i = 0; $i < $item_index; $i++) {
			$container_name = $path_arr[$i];
			/** @var $container ContainerInterface */
			$container = $container->get($container_name);
		}
		return [$container, $item_name];
	}

	abstract protected function getItem($name);

	abstract protected function hasItem($name) : bool;

}
