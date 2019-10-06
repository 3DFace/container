<?php
/* author: Ponomarev Denis <ponomarev@gmail.com> */

namespace dface\container;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException as InteropContainerException;
use Interop\Container\Exception\NotFoundException as InteropNotFoundException;

abstract class HandyContainer implements \ArrayAccess, ContainerInterface
{

	/**
	 * @param mixed $name
	 * @return bool|mixed
	 * @throws InteropContainerException
	 */
	public function offsetExists($name)
	{
		try{
			[$container, $item_name] = $this->getTargetContainerAndItemName($name);
		}catch (InteropNotFoundException $e){
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
	 * @throws InteropContainerException
	 * @throws InteropNotFoundException
	 */
	public function offsetGet($name)
	{
		try{
			[$container, $item_name] = $this->getTargetContainerAndItemName($name);
			if (!$container instanceof ContainerInterface) {
				$type = \gettype($container);
				$relative_name = \substr($name, 0, -\strlen($item_name));
				throw new ContainerException("'$relative_name' expected to be a ContainerInterface, got '$type'");
			}
			return $container->get($item_name);
		}catch (InteropNotFoundException $e){
			throw new NotFoundException("'$name' not found", 0, $e);
		}
	}

	public function offsetSet($offset, $value) : void
	{
		throw new \RuntimeException('Unsupported container access');
	}

	public function offsetUnset($offset) : void
	{
		throw new \RuntimeException('Unsupported container access');
	}

	public function get($id)
	{
		return $this->getItem($id);
	}

	public function has($id) : bool
	{
		return $this->hasItem($id);
	}

	public function __invoke($id)
	{
		return $this->getItem($id);
	}

	/**
	 * @param string $name
	 * @return array
	 * @throws InteropContainerException
	 * @throws InteropNotFoundException
	 */
	private function getTargetContainerAndItemName(string $name) : array
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
