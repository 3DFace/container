<?php

namespace dface\container;

use Psr\Container\ContainerInterface;

class AContainer extends BaseContainer
{

	/** @var ContainerInterface */
	private $container;
	/** @var string[] */
	private $names;

	public function __construct(array $definitions = [], ContainerInterface $parent = null)
	{
		$lookup = $parent ? new ContainerLink($this, $parent) : $this;
		$factories = new FactoryContainer($definitions, $lookup);
		$singleton = new SingletonContainer($factories);
		$this->container = new PathResolver($singleton);
		$this->names = \array_keys($definitions);
	}

	public function get($id)
	{
		return $this->container->get($id);
	}

	public function has($id) : bool
	{
		return $this->container->has($id);
	}

	public function getNames() : array
	{
		return $this->names;
	}

}
