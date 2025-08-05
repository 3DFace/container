<?php

namespace dface\container;

use Psr\Container\ContainerInterface;

class GenericContainer extends BaseContainer implements DiscoverableContainer
{

	private SingletonContainer $container;
	private FactoryContainer $factories;

	public function __construct(DefinitionSource $definitions, ContainerInterface $parent = null)
	{
		$lookup = $parent ? new ContainerJoin($this, $parent) : $this;
		$this->factories = new FactoryContainer($definitions, $lookup);
		$this->container = new SingletonContainer($this->factories);
	}

	public function get(string $id) : mixed
	{
		return $this->container->get($id);
	}

	public function has(string $id) : bool
	{
		return $this->container->has($id);
	}

	public function getNames() : iterable
	{
		return $this->factories->getNames();
	}

}
