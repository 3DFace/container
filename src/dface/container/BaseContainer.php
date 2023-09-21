<?php

namespace dface\container;

use Psr\Container\ContainerInterface;

abstract class BaseContainer implements \ArrayAccess, ContainerInterface
{

	/**
	 * @param mixed $offset
	 * @return bool|mixed
	 */
	public function offsetExists($offset) : bool
	{
		return PathResolver::containerHasPath($this, $offset);
	}

	/**
	 * @param mixed $offset
	 * @return mixed
	 * @throws ContainerException
	 */
	#[\ReturnTypeWillChange]
	public function offsetGet($offset)
	{
		return PathResolver::containerGetPath($this, $offset);
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
		return $this->offsetGet($name);
	}

	public function hasItem($name) : bool
	{
		return $this->offsetExists($name);
	}

}
