<?php
/* author: Ponomarev Denis <ponomarev@gmail.com> */

namespace dface\container;

use Interop\Container\ContainerInterface;

class SingletonContainer extends BaseContainer
{

	/** @var ContainerInterface */
	protected $factory;
	protected $items = [];

	/**
	 * SingletonContainer constructor.
	 * @param ContainerInterface $factory
	 */
	public function __construct(ContainerInterface $factory)
	{
		$this->factory = $factory;
	}

	public function hasItem($name) : bool
	{
		return array_key_exists($name, $this->items) || $this->factory->has($name);
	}

	/**
	 * @param $name
	 * @return mixed
	 * @throws \Interop\Container\Exception\ContainerException
	 * @throws \Interop\Container\Exception\NotFoundException
	 */
	public function getItem($name)
	{
		if (array_key_exists($name, $this->items)) {
			return $this->items[$name];
		}
		$item = $this->factory->get($name);
		$this->items[$name] = $item;
		return $item;
	}

}
