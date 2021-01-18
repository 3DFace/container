<?php

namespace dface\container;

use Psr\Container\ContainerInterface;

class SingletonContainer implements ContainerInterface
{

	private ContainerInterface $factory;
	private array $items = [];

	public function __construct(ContainerInterface $factory)
	{
		$this->factory = $factory;
	}

	public function has($id) : bool
	{
		return \array_key_exists($id, $this->items) || $this->factory->has($id);
	}

	public function get($id)
	{
		if (\array_key_exists($id, $this->items)) {
			return $this->items[$id];
		}
		$item = $this->factory->get($id);
		$this->items[$id] = $item;
		return $item;
	}

}
