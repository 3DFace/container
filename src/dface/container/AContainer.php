<?php

namespace dface\container;

use Psr\Container\ContainerInterface;

class AContainer extends BaseContainer
{

	/** @var ContainerInterface */
	private $container;
	/** @var FactoryContainer */
	private $factories;

	public function __construct(array $definitions = [], ContainerInterface $parent = null)
	{
		$lookup = $parent ? new ContainerJoin($this, $parent) : $this;
		$this->factories = new FactoryContainer($definitions, $lookup);
		$this->container = new SingletonContainer($this->factories);
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
		return $this->factories->getNames();
	}

}
