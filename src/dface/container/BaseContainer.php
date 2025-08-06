<?php

namespace dface\container;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;

class BaseContainer implements \ArrayAccess, ContainerInterface, Container
{

	public function __construct(private readonly ContainerInterface $container)
	{
	}

	public function get(string $id) : mixed
	{
		return PathResolver::containerGetPath($this->container, $id);
	}

	/**
	 * @throws ContainerExceptionInterface
	 */
	public function has(string $id) : bool
	{
		return PathResolver::containerHasPath($this->container, $id);
	}

	/**
	 * @param mixed $offset
	 * @return bool
	 * @throws ContainerExceptionInterface
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
	 * @throws ContainerExceptionInterface
	 * @deprecated
	 */
	public function hasItem(string $name) : bool
	{
		return $this->has($name);
	}

}
