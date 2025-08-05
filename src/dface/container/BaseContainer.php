<?php

namespace dface\container;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;

abstract class BaseContainer implements \ArrayAccess, ContainerInterface, Container
{

	/**
	 * @param mixed $offset
	 * @return bool
	 * @throws ContainerExceptionInterface
	 */
	public function offsetExists(mixed $offset) : bool
	{
		return PathResolver::containerHasPath($this, (string)$offset);
	}

	/**
	 * @param mixed $offset
	 * @return mixed
	 * @throws ContainerExceptionInterface
	 */
	public function offsetGet(mixed $offset) : mixed
	{
		return PathResolver::containerGetPath($this, (string)$offset);
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
		return $this->offsetGet($name);
	}

	/**
	 * Use 'has'
	 * @deprecated
	 * @throws ContainerExceptionInterface
	 */
	public function hasItem(string $name) : bool
	{
		return $this->offsetExists($name);
	}

}
