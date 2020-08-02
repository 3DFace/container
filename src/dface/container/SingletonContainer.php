<?php
/* author: Ponomarev Denis <ponomarev@gmail.com> */

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

	public function has($name) : bool
	{
		return \array_key_exists($name, $this->items) || $this->factory->has($name);
	}

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
