<?php
/* author: Ponomarev Denis <ponomarev@gmail.com> */

namespace dface\container;

use Psr\Container\ContainerInterface;

abstract class BaseContainer implements \ArrayAccess, ContainerInterface
{

	/**
	 * @param mixed $name
	 * @return bool|mixed
	 */
	public function offsetExists($name)
	{
		return PathResolver::containerHasPath($this, $name);
	}

	/**
	 * @param mixed $name
	 * @return mixed
	 * @throws ContainerException
	 */
	public function offsetGet($name)
	{
		return PathResolver::containerGetPath($this, $name);
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
