<?php

namespace dface\container;

use Psr\Container\ContainerExceptionInterface;
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
		return PathResolver::containerGetPath($this->container, $id);
	}

	/**
	 * @throws ContainerExceptionInterface
	 */
	public function has(string $id) : bool
	{
		return PathResolver::containerHasPath($this->container, $id);
	}

	public function getNames() : iterable
	{
		return $this->factories->getNames();
	}

}
