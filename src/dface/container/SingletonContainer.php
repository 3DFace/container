<?php
/* author: Ponomarev Denis <ponomarev@gmail.com> */

namespace dface\container;

use Interop\Container\ContainerInterface;

class SingletonContainer implements ContainerInterface
{

	/** @var ContainerInterface */
	private $factory;
	private $items = [];

	/**
	 * SingletonContainer constructor.
	 * @param ContainerInterface $factory
	 */
	public function __construct(ContainerInterface $factory)
	{
		$this->factory = $factory;
	}

	public function has($name) : bool
	{
		return \array_key_exists($name, $this->items) || $this->factory->has($name);
	}

	/**
	 * @param $name
	 * @return mixed
	 * @throws \Interop\Container\Exception\ContainerException
	 * @throws \Interop\Container\Exception\NotFoundException
	 */
	public function get($name)
	{
		if (\array_key_exists($name, $this->items)) {
			return $this->items[$name];
		}
		$item = $this->factory->get($name);
		$this->items[$name] = $item;
		return $item;
	}

}
