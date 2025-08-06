<?php

namespace dface\container;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;

abstract class BaseContainer implements \ArrayAccess, ContainerInterface, Container
{

	/**
	 * @param mixed $offset
	 * @return bool
	 */
	public function offsetExists(mixed $offset) : bool
	{
		return $this->has($offset);
	}

	/**
	 * @param mixed $offset
	 * @return mixed
	 * @throws ContainerExceptionInterface
	 */
	public function offsetGet(mixed $offset) : mixed
	{
		return $this->get($offset);
	}

	/**
	 * @throws ContainerExceptionInterface
	 */
	public function offsetSet(mixed $offset, $value) : void
	{
		throw new ContainerException('Unsupported container access');
	}

	/**
	 * @throws ContainerExceptionInterface
	 */
	public function offsetUnset(mixed $offset) : void
	{
		throw new ContainerException('Unsupported container access');
	}

	/**
	 * @throws ContainerExceptionInterface
	 */
	public function __invoke(string $id) : mixed
	{
		return $this->offsetGet($id);
	}

	/**
	 * Use `get`
	 * @deprecated
	 * @throws ContainerExceptionInterface
	 */
	public function getItem(string $name) : mixed
	{
		return $this->get($name);
	}

	/**
	 * Use 'has'
	 * @deprecated
	 */
	public function hasItem(string $name) : bool
	{
		return $this->has($name);
	}

}
