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

	public function __invoke($id)
	{
		return $this->getItem($id);
	}

	public function getItem($name)
	{
		return $this->get($name); // legacy
	}

	public function hasItem($name) : bool
	{
		return $this->has($name); // legacy
	}

}
